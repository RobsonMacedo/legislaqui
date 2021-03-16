<?php

namespace App\Http\Controllers;

use App\Data\Models\Subject;
use App\Data\Repositories\Subjects;
use App\Enums\ProposalState;
use App\Events\ProposalChanged;
use App\Events\ProposalWasCreated;
use App\Http\Requests\ProposalStoreRequest;
use App\Http\Requests\ProposalSupportRequest;
use App\Http\Requests\ProposalFollowRequest;
use App\Http\Requests\ProposalUnfollowRequest;
use App\Http\Requests\ProposalUpdateRequest;
use App\Http\Requests\ResponseFormRequest;
use App\Data\Models\Like;
use App\Data\Models\Proposal;
use App\Data\Models\ProposalFollow;
use App\Data\Models\ProposalHistory;
use App\Repositories\ProposalsRepository;
use Auth;
use Carbon\Carbon;
use Cookie;
use Gate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ProposalSearchRequest;

class ProposalsController extends Controller
{
    /**
     * @var ProposalsRepository
     */
    private $proposalsRepository;

    public function __construct(ProposalsRepository $proposalsRepository)
    {
        $this->proposalsRepository = $proposalsRepository;
    }

    public function index(ProposalSearchRequest $request)
    {
        $data = $request->all() ?? [];
        $state = $data['state'] ?? null;
        $searchString = $data['search'] ?? null;
        $selected_subjects = $data['selected_subjects'] ?? [];

        $query = $this->proposalsRepository->filterProposals(
            $state,
            $selected_subjects,
            $searchString
        );

        $query = $this->proposalsRepository->orderBy($query, $data);

        $proposals = $this->proposalsRepository->paginate($query, $data);

        return view('proposals.index')
            ->with('state', $state)
            ->with('search', $searchString)
            ->with('proposals', $proposals)
            ->with('states', ProposalState::filterStates())
            ->with($this->proposalsRepository->getViewVariables($data))
            ->with($this->proposalsRepository->getOrderByVariables($data))
            ->with('subjects', Subject::all()->pluck('name', 'id'))
            ->with('selected_subjects', $selected_subjects);
    }

    public function approval($id, ProposalSupportRequest $request)
    {
        $this->proposalsRepository->approve($id);

        return redirect()->back();
    }

    public function committee()
    {
        return view('proposals.index')->with(
            'proposals',
            Proposal::where('in_committee', true)
                ->orderBy('created_at', 'desc')
                ->paginate(config('global.pagination'))
        );
    }

    public function create()
    {
        return view('proposals.create')->with('subjects', app(Subjects::class)->getSelectOptions());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $proposal = $this->proposalsRepository->find($id);

        if (Gate::allows('destroy', $proposal)) {
            $proposal->delete();

            return redirect()
                ->route('proposals')
                ->with('proposal_crud_msg', 'Ideia Legislativa Removida com Sucesso');
        } else {
            return redirect()
                ->route('proposals')
                ->with('error_msg', 'Você não é o dono desta Ideia Legislativa');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //Get Proposal
        $proposal = $this->proposalsRepository->find($id);

        if (Gate::allows('edit', $proposal)) {
            return view('proposals.edit')
                ->with('proposal', $proposal)
                ->with('subjects', app(Subjects::class)->getSelectOptions());
        } else {
            return redirect()
                ->route('proposals')
                ->with('error_msg', 'Você não é o dono desta Ideia Legislativa');
        }
    }

    public function finished()
    {
        return view('proposals.index')->with(
            'proposals',
            Proposal::where('open', false)
                ->orderBy('created_at', 'desc')
                ->paginate(config('global.pagination'))
        );
    }

    /**
     * Store Proposal Follow information.
     */
    public function follow($id, ProposalFollowRequest $request)
    {
        $follow = $this->proposalsRepository->follow($id, Auth::user()->id);

        if ($follow->wasRecentlyCreated) {
            Session::flash('flash_msg', 'Esta Ideia Legislativa será acompanhada! Obrigado.');
        } else {
            Session::flash('flash_msg', 'Esta Ideia Legislativa já está sendo acompanhada!');
        }

        $proposal = $this->proposalsRepository->find($id);

        return redirect()->route('proposal.show', ['proposal' => $proposal]);
    }

    /**
     * Store Proposal Follow information.
     */
    public function unfollow($id, ProposalUnfollowRequest $request)
    {
        //Get Proposal
        $success = $this->proposalsRepository->unfollow($id, Auth::user()->id);

        if ($success) {
            Session::flash('flash_msg', 'Esta Ideia Legislativa não será mais acompanhada.');
        } else {
            Session::flash('flash_msg', 'Você não está acompanhando essa ideia legislativa.');
        }

        $proposal = $this->proposalsRepository->find($id);

        return redirect()->route('proposal.show', ['proposal' => $proposal]);
    }

    public function like($id)
    {
        return $this->likeUnlike($id, 'like');
    }

    public function likeUnlike($id, $action)
    {
        //Get Proposal
        $proposal = $this->proposalsRepository->find($id);

        //Get User
        if (!Auth::user()) {
            // The user is not logged in...
            // Retrieve UUID from Cookie
            $user_id = null;
            $unique = Cookie::get('uuid');
        } else {
            //Retrieve UUID from User
            $user_id = Auth::user()->id;
            $unique = Auth::user()->uuid;
        }

        //Possible Values: Null, 0 or 1
        $existing_like = Like::where('uuid', $unique)
            ->where('proposal_id', $id)
            ->value('like');

        //        Session::put('flash_msg', 'Task was successful!');

        //        dd(Session::all());

        switch ($existing_like) {
            // Already Unliked
            case '0':
                switch ($action) {
                    case 'like':
                        Like::where('uuid', $unique)
                            ->where('proposal_id', $id)
                            ->update(['like' => $action == 'like']);
                        Session::flash('flash_msg', 'Você voltou a curtir essa Ideia Legislativa!');
                        break;
                    case 'unlike':
                        Session::flash(
                            'error_msg',
                            'Você já deixou de curtir essa Ideia Legislativa!'
                        );
                        break;
                }
                break;
            // Already Liked
            case '1':
                switch ($action) {
                    case 'like':
                        Session::flash('error_msg', 'Você já curtiu essa Ideia Legislativa!');
                        break;
                    case 'unlike':
                        Like::where('uuid', $unique)
                            ->where('proposal_id', $id)
                            ->update(['like' => $action == 'like']);
                        Session::flash(
                            'flash_msg',
                            'Você deixou de curtir essa Ideia Legislativa!'
                        );
                        break;
                }
                break;
            // New Like
            case null:
                //dd($existing_like, $action, $str_action);
                switch ($action) {
                    case 'like':
                        Like::create([
                            'user_id' => $user_id,
                            'uuid' => $unique,
                            'proposal_id' => $proposal->id,
                            'like' => $action == 'like',
                            'ip_address' => Request::ip(),
                        ]);

                        $approval_url = route('proposal.approval', $id);
                        $msg =
                            'Sua curtida foi computada com sucesso. Caso queira apoiar oficialmente esta proposta, <a href="' .
                            $approval_url .
                            '">clique aqui</a>.';
                        Session::flash('flash_msg', $msg);
                        break;
                    case 'unlike':
                        Like::create([
                            'user_id' => $user_id,
                            'uuid' => $unique,
                            'proposal_id' => $proposal->id,
                            'like' => $action == 'like',
                            'ip_address' => Request::ip(),
                        ]);

                        $msg = 'Sua descurtida foi computada com sucesso.';
                        Session::flash('flash_msg', $msg);
                        break;
                }
                break;
        }

        return redirect()->back();
    }

    public function open()
    {
        return view('proposals.index')->with(
            'proposals',
            Proposal::where(['open' => true, 'in_committee' => false])
                ->orderBy('created_at', 'desc')
                ->paginate(config('global.pagination'))
        );
    }

    public function progress()
    {
        return view('proposals.index')->with(
            'proposals',
            Proposal::where('open', true)
                ->orderBy('created_at', 'desc')
                ->paginate(config('global.pagination'))
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function response($id)
    {
        //Get Proposal
        $proposal = $this->proposalsRepository->find($id);

        if (Gate::allows('edit', $proposal)) {
            return view('proposals.response')->with('proposal', $proposal);
        } else {
            return redirect()
                ->route('proposals')
                ->with('error_msg', 'Você não é o dono desta Ideia Legislativa');
        }
    }

    public function show($id)
    {
        if ($proposal = Proposal::withoutGlobalScopes()->findOrFail($id)) {
            if (!$proposal->isPublic() && !$proposal->userCanView(auth()->user()->id ?? null)) {
                Session::flash('flash_msg', 'Para visualizar esta ideia, você deve fazer login');

                return redirect()->guest('login');
            }
        }

        return view('proposals.show', ['proposal' => $proposal]);
    }

    public function store(ProposalStoreRequest $formRequest)
    {
        $input = $formRequest->except('_token');

        $input['user_id'] = Auth::user()->id;
        $input['open'] = true;
        $input['limit_date'] = null;
        //dd($input);

        $proposal = Proposal::create($input);
        $follow = $this->proposalsRepository->follow($proposal->id, Auth::user()->id);
        $proposal->subjects()->sync($formRequest->get('subjects'));

        event(new ProposalWasCreated($proposal));

        return redirect()
            ->route('proposal.show', ['proposal' => $proposal])
            ->with('proposal_crud_msg', 'Ideia Legislativa incluída com sucesso');
    }

    public function unlike($id)
    {
        return $this->likeUnlike($id, 'unlike');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, ProposalUpdateRequest $formRequest)
    {
        $proposal = $this->proposalsRepository->find($id);

        $input = $formRequest->except('_token');

        $input['user_id'] = Auth::user()->id;
        $input['open'] = true;

        //Create ProposalHistory Object
        $proposal_history = new ProposalHistory();
        //Get attributes from Proposals Eloquent

        $proposal_history->setRawAttributes(
            array_except($proposal->getAttributes(), ['id', 'created_at', 'updated_at'])
        );
        //dd($proposal_history);
        //Append Update Info
        $proposal_history->proposal_id = $id;
        $proposal_history->update_id = Auth::user()->id;
        $proposal_history->update_date = Carbon::now();
        //Save History
        $proposal_history->save();

        //Then update Proposal
        $proposal->fill($input)->save();
        $proposal->subjects()->sync($formRequest->get('subjects'));

        event(new ProposalChanged($proposal));

        return redirect()
            ->route('proposal.show', ['proposal' => $proposal])
            ->with('proposal_crud_msg', 'Ideia Legislativa editada com sucesso');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function updateResponse($id, ResponseFormRequest $formRequest)
    {
        $proposal = $this->proposalsRepository->find($id);

        $input = $formRequest->except('_token', '_method');

        $input['responder_id'] = Auth::user()->id;

        //Create ProposalHistory Object
        $proposal_history = new ProposalHistory();
        //Get attributes from Proposals Eloquent
        $proposal_history->setRawAttributes(
            array_except($proposal->getAttributes(), ['id', 'created_at', 'updated_at'])
        );

        //Append Update Info + Response
        $proposal_history->proposal_id = $id;
        $proposal_history->update_id = Auth::user()->id;
        $proposal_history->update_date = Carbon::now();
        $proposal_history->response = $input['response'];
        $proposal_history->responder_id = $input['responder_id'];

        //Save History
        $proposal_history->save();

        //Then update Proposal
        $proposal->forcefill($input)->save();

        return redirect()
            ->route('proposals')
            ->with('proposal_crud_msg', 'Ideia Legislativa respondida com sucesso');
    }
}

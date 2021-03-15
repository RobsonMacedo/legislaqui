<?php

namespace Tests\Browser\Pages;

use App\Data\Models\Proposal;
use App\Enums\ProposalState;
use App\Repositories\RolesRepository;
use App\Support\Constants;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Data\Models\User;
use App\Support\helpers;
use Faker\Generator as Faker;

class AplicationFlowTest extends DuskTestCase
{
    private static $cidadao;
    private static $approval;
    private static $commission;
    private static $newProposal;

    public function init()
    {
        static::$newProposal = factory(Proposal::class)->raw();
        static::$cidadao = User::all()
            ->where('role_id', app(RolesRepository::class)->findIdByRole(Constants::ROLE_CIDADAO))
            ->random()
            ->toArray();
        static::$approval = User::all()
            ->where('role_id', app(RolesRepository::class)->findIdByRole(Constants::ROLE_APPROVAL))
            ->random()
            ->toArray();
        static::$commission = User::all()
            ->where(
                'role_id',
                app(RolesRepository::class)->findIdByRole(Constants::ROLE_COMMISSION)
            )
            ->random()
            ->toArray();
    }

    public function search($browser, $ideia, $array)
    {
        foreach ($array as $key => $bool) {
            //dump(str_replace('\\', '', $key));

            $browser
                ->visit('/proposals')
                ->type('@proposal-search', $ideia['name'])
                ->press('@filterButton')
                ->pause(2000)
                ->select('state', $key)
                //->screenshot(str_replace('\\', '', $key))
                ->press('@filterButton')
                ->pause(1000);

            if ($bool) {
                $browser->assertSee($ideia['idea_exposition']);
            } else {
                $browser->assertDontSee($ideia['idea_exposition']);
            }
        }
    }
    public function find($id)
    {
        return Proposal::findOrFail($id);
    }

    public function support($id, $browser, $url)
    {
        $proposal = $this->find($id);
        do {
            $user = User::all()
                ->random()
                ->toArray();
            $total_support = $proposal
                ->approvals()
                ->where('proposal_id', $id)
                ->get()
                ->count();

            $browser
                ->loginAs($user['id'])
                ->visit($url)
                ->press('@support')
                ->pause(2000);
        } while ($total_support <= config('global.approvalGoal'));
        return $total_support;
    }

    public function testFlow()
    {
        $this->init();
        $citizen = static::$cidadao;
        $approval = static::$approval;
        $newProposal = static::$newProposal;
        $answer = app(Faker::class)->paragraph();
        $owner_name = app(Faker::class)->name();
        $comission = static::$commission;

        $allArray = [
            json_encode([ProposalState::All]) => true,
            json_encode(ProposalState::openStates()) => false,
            json_encode([ProposalState::Supported]) => false,
            json_encode([ProposalState::Sent]) => false,
            json_encode([ProposalState::Expired]) => false,
            json_encode([ProposalState::BillProject]) => false,
        ];

        $openProposalsArray = [
            json_encode([ProposalState::All]) => true,
            json_encode(ProposalState::openStates()) => true,
            json_encode([ProposalState::Supported]) => false,
            json_encode([ProposalState::Sent]) => false,
            json_encode([ProposalState::Expired]) => false,
            json_encode([ProposalState::BillProject]) => false,
        ];

        $sentToCommitteeArray = [
            json_encode([ProposalState::All]) => true,
            json_encode(ProposalState::openStates()) => false,
            json_encode([ProposalState::Supported]) => false,
            json_encode([ProposalState::Sent]) => true,
            json_encode([ProposalState::Expired]) => false,
            json_encode([ProposalState::BillProject]) => false,
        ];

        $inDiscussionArray = [
            json_encode([ProposalState::All]) => true,
            json_encode(ProposalState::openStates()) => false,
            json_encode([ProposalState::Supported]) => false,
            json_encode([ProposalState::Sent]) => false,
            json_encode([ProposalState::Expired]) => false,
            json_encode([ProposalState::BillProject]) => false,
        ];

        $billProjectArray = [
            json_encode([ProposalState::All]) => true,
            json_encode(ProposalState::openStates()) => false,
            json_encode([ProposalState::Supported]) => false,
            json_encode([ProposalState::Sent]) => false,
            json_encode([ProposalState::Expired]) => false,
            json_encode([ProposalState::BillProject]) => true,
        ];

        $this->browse(function (Browser $cidadao, Browser $aprovador, Browser $comissao) use (
            $citizen,
            $newProposal,
            $allArray,
            $approval,
            $answer,
            $openProposalsArray,
            $sentToCommitteeArray,
            $comission,
            $billProjectArray,
            $inDiscussionArray,
            $owner_name
        ) {
            //Cidadão cria a ideia
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('/')
                ->click('@newProposalButton')
                ->assertSee('Propor Ideia Legislativa')
                ->type('@name_field', $newProposal['name'])
                ->type('@exposionidea_field', $newProposal['idea_exposition'])
                ->click('@submitbuttonproposal')
                ->assertSee('Aguardando moderação')
                //->screenshot('1-citizen_create_the_proposal')
                ->pause(3000);
            $url = $cidadao->driver->getCurrentURL();
            $last = explode('/', $url);
            $id_proposal = end($last);
            $this->search($cidadao, $newProposal, $allArray);
            $this->assertDatabaseHas('proposals', ['name' => $newProposal['name']]);

            //Aprovador aprova a ideia
            $aprovador
                ->loginAs($approval['id'])
                ->visit('/admin/proposals/' . $id_proposal . '/response')
                ->type('response', $answer)
                ->press('@approve')
                ->waitforText(
                    'Ideia Legislativa Aprovada com Sucesso'
                    //->screenshot('2-approval_approved_proposal')
                );
            $cidadao->loginAs($citizen['id']);
            $this->search($cidadao, $newProposal, $openProposalsArray);

            ////Cidadãos apoiam a ideia
            $this->support($id_proposal, $cidadao, $url);

            //Cidadão ver que alcançou apoios suficientes
            $cidadao->visit('proposals/' . $id_proposal)->assertSee('Alcançou apoios suficientes');

            //Aprovador vai mandar ára a comissão
            $aprovador
                ->loginAs($approval['id'])
                ->visit('/admin/proposals/approval-goal')
                ->visit('/admin/proposals/' . $id_proposal . '/to-committee') //TODO: usar o botão
                ->pause(1000)
                //->screenshot('3-proposal_sent_to_comission')
                ->pause(2000);

            //Cidadão vai ver que foi enviado para a comissão
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/' . $id_proposal)
                ->assertSee(
                    'Enviada para a comissão'
                    //->screenshot('4-Enviada para a comissão')
                );
            $this->search($cidadao, $newProposal, $sentToCommitteeArray);

            //Usuário de comissão vai marcar "em discussão"
            $comissao
                ->loginAs($comission['id'])
                ->visit('/admin/proposals/in-committee')
                ->visit('/admin/proposals/' . $id_proposal . '/committee-approval') //TODO: usar o botão
                //->screenshot('5-comission_approved_proposal')
                ->pause(2000);

            //Cidadão vai ver que foi marcado como em discussão
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/' . $id_proposal)
                ->assertSee(
                    'Em discussão pela comissão'
                    //->screenshot('6-Em discussão pela comissão')
                );
            $this->search($cidadao, $newProposal, $inDiscussionArray);

            //Usuário de comissão vai assignar projeto de lei
            $comissao
                ->loginAs($comission['id'])
                ->visit('/admin/proposals/approved-by-committee')
                ->visit('/admin/proposals/' . $id_proposal . '/bill-project') //TODO: usar o botão
                ->type('@number', random_int(1, 50))
                ->type('@year', random_int(2019, 2021))
                ->type('@owner', $owner_name)
                ->type('@link', $url)
                ->screenshot('7-comission_assigned_proposal')
                ->press('@submit_button-billproject')
                ->pause(2000);

            //Cidadão vai ver que foi assignado projeto de lei
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/' . $id_proposal)
                ->assertSee('Virou projeto de lei')
                ->screenshot('8-VIROU PROJETO DE LEI');
            $this->search($cidadao, $newProposal, $billProjectArray);
        });
    }
}

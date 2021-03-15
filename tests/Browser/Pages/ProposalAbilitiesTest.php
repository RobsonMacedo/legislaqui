<?php

namespace Tests\Browser\Pages;

use App\Data\Models\User;
use App\Data\Models\Proposal;
use App\Enums\ProposalState;
use App\Enums\ProposalState as State;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProposalAbilitiesTest extends DuskTestCase
{
    private static $newProposal;
    private static $randomProposal;
    private static $newUser;
    private static $randomUser;

    public function init()
    {
        static::$newProposal = factory(Proposal::class)->raw();
        static::$randomProposal = Proposal::all()
            ->random()
            ->toArray();
        static::$newUser = factory(User::class)->raw();
        static::$randomUser = User::all()
            ->random()
            ->load('proposals')
            ->toArray();
    }

    public function testCreateProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $newProposal = static::$newProposal;

        $this->browse(function (Browser $browser) use ($randomUser, $newProposal) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/')
                ->click('@newProposalButton')
                ->assertSee('Propor Ideia Legislativa')
                ->type('@name_field', $newProposal['name'])
                ->type('@exposionidea_field', $newProposal['idea_exposition'])
                ->screenshot('filledProposal-created')
                ->click('@submitbuttonproposal')
                ->pause(5000)
                ->assertSee($newProposal['idea_exposition'])
                ->screenshot('proposalSuccessfullyCreated');
        });
        $this->assertDatabaseHas('proposals', ['name' => $newProposal['name']]);
    }

    public function testIncludeProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal = static::$randomProposal;
        $newProposal = static::$newProposal;

        $this->browse(function (Browser $browser) use ($randomUser, $randomProposal, $newProposal) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal['id'])
                ->click('@novaIdeia')
                ->type('@name_field', $newProposal['name'])
                ->type('@exposionidea_field', $newProposal['idea_exposition'])
                ->screenshot('filledProposal-included')
                ->click('@submitbuttonproposal')
                ->pause(5000)
                ->assertSee($newProposal['idea_exposition'])
                ->screenshot('proposalSuccessfullyIncluded');
        });
        $this->assertDatabaseHas('proposals', ['name' => $newProposal['name']]);
    }

    public function testEditProposal()
    {
        $this->init();
        $randomUser = static::$randomUser; //TODO: Esse usuário deve ter garantidamente uma proposal ao menos

        $randomProposal = DB::table('proposals')
            ->where('approved_at', '=', null)
            ->where('approved_by', '=', null)
            ->where('disapproved_by', '=', null)
            ->where('user_id', '=', $randomUser['id'])
            ->inRandomOrder()
            ->first();

        $id = $randomProposal->id;
        $this->browse(function (Browser $browser) use ($randomUser, $randomProposal, $id) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $id)
                ->click('@editIdea')
                ->type('@name-edit_field', $randomProposal->name . '**')
                ->type('@exposionidea-edit_field', $randomProposal->idea_exposition . '**')
                ->click('@savebutton')
                ->pause(5000)
                ->assertSee($randomProposal->idea_exposition . '**')
                ->screenshot('proposalSuccessfullyEdited');
        });
        $this->assertDatabaseHas('proposals', [
            'name' => $randomProposal->name . '**',
            'idea_exposition' => $randomProposal->idea_exposition . '**',
        ]);
    }
}

<?php

namespace Tests\Browser\Pages;

use App\Data\Models\User;
use App\Data\Models\Proposal;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Data\Models\State;

class SocialLoginTest extends DuskTestCase
{

    public function testSocialLoginFacebook()
    {
        $this->browse(function (Browser $browser)
        {
            $browser
                ->visit('/login')
                ->assertSee('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomFacebookLogin')
                ->screenshot('first')
                ->type('#email', 'testealerj@gmail.com')
                ->type('#pass', 'Alerjteste123')
                ->screenshot('second')
                ->press('#loginbutton')
                ->pause(2000)
                ->screenshot('third');
        });

    }

/*     public function testIncludeProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal = static::$randomProposal;
        $newProposal = static::$newProposal;

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal,
            $newProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal['id'])
                ->click('@novaIdeia')
                ->type('@name_field', $newProposal['name'])
                ->type('@problem_field', $newProposal['problem'])
                ->type('@exposionidea_field', $newProposal['idea_exposition'])
                ->screenshot('filledProposal-included')
                ->click('@submitbuttonproposal')
                ->pause(5000)
                ->assertSee($newProposal['problem'])
                ->screenshot('proposalSuccessfullyIncluded');
        });
        $this->assertDatabaseHas('proposals', ['name' => $newProposal['name']]);
    }

    public function testEditProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal1 = Proposal::all()->random();
        $randomProposal1->user_id = $randomUser['id'];
        $randomProposal1->save();
        $randomProposal = Proposal::find($randomProposal1->id);

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal['id'])
                ->click('@editIdea')
                ->type('@name-edit_field', $randomProposal['name'] . '**')
                ->type('@problem-edit_field', $randomProposal['problem'] . '**')
                ->type(
                    '@exposionidea-edit_field',
                    $randomProposal['idea_exposition'] . '**'
                )
                ->click('@savebutton')
                ->pause(5000)
                ->assertSee($randomProposal['problem'] . '**')
                ->screenshot('proposalSuccessfullyEdited');
        });
        $this->assertDatabaseHas('proposals', [
            'name' => $randomProposal['name'] . '**',
            'problem' => $randomProposal['problem'] . '**'
        ]);
    }
 */}

<?php

namespace Tests\Browser\Pages;

use App\Data\Models\User;
use App\Data\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProposalInteractionsTest extends DuskTestCase
{
    private static $randomProposal;
    private static $randomUser;

    public function init()
    {
        static::$randomProposal = Proposal::all()
            ->random()
            ->toArray();
        static::$randomUser = User::all()
            ->random()
            ->toArray();
    }

    /**
     * @test
     * @group testLikeProposal
     * @group link
     */
    public function testLikeProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal = static::$randomProposal;

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal['id'])
                ->press('@like')
                ->assertDontSeeLink(
                    'Sua curtida foi computada com sucesso. Caso queira apoiar oficialmente esta proposta, clique aqui.'
                )
                ->screenshot('proposalSuccessfullyLiked');
        });
        $this->assertDatabaseHas('likes', [
            'like' => 1,
            'proposal_id' => $randomProposal['id'],
            'user_id' => $randomUser['id']
        ]);
    }

    /**
     * @test
     * @group testDisLikeProposal
     * @group link
     */
    public function testDisLikeProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal = static::$randomProposal;

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal['id'])
                ->press('@dislike')
                ->assertDontSeeLink('Sua descurtida foi computada com sucesso.')
                ->screenshot('proposalSuccessfullyDisLiked');
        });
        $this->assertDatabaseHas('likes', [
            'like' => 0,
            'proposal_id' => $randomProposal['id'],
            'user_id' => $randomUser['id']
        ]);
    }

    /**
     * @test
     * @group testSupportProposal
     * @group link
     */
    public function testSupportProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal =  DB::table('proposals')->whereNotNull('approved_at')->whereNotNull('approved_by')->inRandomOrder()->first();

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal->id)
                ->press('@support')
                ->assertDontSeeLink('Seu apoio foi incluído com sucesso.')
                ->screenshot('proposalSuccessfullySupported');
        });
    }

    /**
     * @test
     * @group testFollowProposal
     * @group link
     */
    public function testFollowProposal()
    {
        $this->init();
        $randomUser = static::$randomUser;
        $randomProposal = DB::table('proposals')->whereNotNull('approved_at')->whereNotNull('approved_by')->where('responder_id' ,'=',null)->inRandomOrder()->first();

        $this->browse(function (Browser $browser) use (
            $randomUser,
            $randomProposal
        ) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/proposals/' . $randomProposal->id)
                ->press('@follow')
                ->assertDontSeeLink(
                    'Esta Ideia Legislativa será acompanhada! Obrigado.'
                )
                ->screenshot('proposalSuccessfullyFollowed');
        });
        $this->assertDatabaseHas('proposal_follows', [
            'user_id' => $randomUser['id'],
            'proposal_id' => $randomProposal->id
        ]);
    }
}

<?php


namespace Tests\Browser\Pages;


use App\Data\Models\Proposal;
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
        static::$newProposal =  factory(Proposal::class)->raw();
        static::$cidadao = User::all()->where(
            'role_id', app(RolesRepository::class)->findIdByRole(Constants::ROLE_CIDADAO)
        )->random()->toArray();
        static::$approval = User::all()->where(
            'role_id', app(RolesRepository::class)->findIdByRole(Constants::ROLE_APPROVAL)
        )->random()->toArray();
        static::$commission = User::all()->where(
            'role_id', app(RolesRepository::class)->findIdByRole(Constants::ROLE_COMMISSION)
        )->random()->toArray();

    }

    public function search($browser,$ideia,$array)
    {
        foreach ($array as $item) {
                $browser
                    ->visit('/proposals')
                    ->type('@proposal-search',$ideia['name'])
                    ->press('@filterButton')
                    ->pause(2000)
                    ->select('proposal_state',$item)
                    ->press('@filterButton')
                    ->pause(1000);
                    if($item){
                        $browser
                            ->assertSee($ideia['idea_exposition']);
                    }else{
                        $browser
                            ->assertDontSee($ideia['idea_exposition']);
            }
        }
    }
    public function find($id)
    {
        return Proposal::findOrFail($id);
    }

    public function support($id,$browser,$url)
    {
        $proposal = $this->find($id);
        do {
            $user = User::all()->random()->toArray();
            $total_support = $proposal
                ->approvals()
                ->where('proposal_id',$id)
                ->get()
                ->count();

            $browser
                ->loginAs($user['id'])
                ->visit($url)
                ->press('@support')
                ->pause(2000)
                ->screenshot('supported');

        } while ($total_support <= 5);
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
        $array1 = [
            true => '["Todas"]',
            false => '["Aprovadas","Alcan\u00e7aram apoios suficientes"]',
            false => '["Alcan\u00e7aram apoios suficientes"]',
            false => '["Enviadas para a Comiss\u00e3o de Normas"]',
            false => '["Expiradas"]',
            false => '["Viraram projeto de lei"]'];
        $array2 =  [
            true => '["Todas"]',
            true => '["Aprovadas","Alcan\u00e7aram apoios suficientes"]',
            false => '["Alcan\u00e7aram apoios suficientes"]',
            false => '["Enviadas para a Comiss\u00e3o de Normas"]',
            false => '["Expiradas"]',
            false => '["Viraram projeto de lei"]'];
        $array3 =  [
            true => '["Todas"]',
            true => '["Aprovadas","Alcan\u00e7aram apoios suficientes"]',
            true => '["Alcan\u00e7aram apoios suficientes"]',
            false => '["Enviadas para a Comiss\u00e3o de Normas"]',
            false => '["Expiradas"]',
            false => '["Viraram projeto de lei"]'];
        $array4 =  [
            true => '["Todas"]',
            false => '["Aprovadas","Alcan\u00e7aram apoios suficientes"]',
            false => '["Alcan\u00e7aram apoios suficientes"]',
            false => '["Enviadas para a Comiss\u00e3o de Normas"]',
            false => '["Expiradas"]',
            true => '["Viraram projeto de lei"]'];



        $this->browse(function (Browser $cidadao,Browser $aprovador,Browser $comissao) use (
            $citizen,
            $newProposal,
            $array1,
            $approval,
            $answer,
            $array2,
            $array3,
            $comission,
            $array4,
            $owner_name
        ) {
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('/')
                ->click('@newProposalButton')
                ->assertSee('Propor Ideia Legislativa')
                ->type('@name_field',  $newProposal['name'])
                ->type('@exposionidea_field',  $newProposal['idea_exposition'])
                ->click('@submitbuttonproposal')
                ->assertSee('Aguardando moderação')
                ->screenshot('1-citizen_create_the_proposal')
                ->pause(3000);
                $url = $cidadao->driver->getCurrentURL();
                $last = explode("/", $url);
                $id_proposal = end($last);
                $this->search($cidadao,$newProposal,$array1);
                $this->assertDatabaseHas('proposals', ['name' =>  $newProposal['name']]);
            $aprovador
                ->loginAs($approval['id'])
                ->visit('/admin/proposals/'.$id_proposal)
                ->press('@proposal_moderate')
                ->assertSee($newProposal['idea_exposition'])
                ->type('response',$answer)
                ->press('@approve')
                ->waitforText('Ideia Legislativa Aprovada com Sucesso')
                ->screenshot('2-approval_approved_proposal');
            $cidadao
                ->loginAs($citizen['id']);
            $this->search($cidadao,$newProposal,$array2);
////           //Cidadão de 2 até 7
            $this->support($id_proposal,$cidadao,$url);
            $cidadao
                ->visit('proposals/'.$id_proposal)
                ->assertSee('Alcançou apoios suficientes');
            $aprovador
                ->loginAs($approval['id'])
                ->visit('/admin/proposals/approval-goal')
                ->visit('/admin/proposals/'.$id_proposal.'/to-committee')
                ->pause(1000)
                ->screenshot('3-proposal_sent_to_comission')
                ->pause(2000);
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/'.$id_proposal)
                ->assertSee('Enviada para a comissão')
                ->screenshot('4-Enviada para a comissão');
            $this->search($cidadao,$newProposal,$array3);
            $comissao
                ->loginAs($comission['id'])
                ->visit('/admin/proposals/in-committee')
                ->visit('/admin/proposals/'.$id_proposal.'/committee-approval')
                ->screenshot('5-comission_approved_proposal')
                ->pause(2000);
////////             Pensar em desaprovar
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/'.$id_proposal)
                ->assertSee('Em discussão pela comissão')
                ->screenshot('6-Em discussão pela comissão');
                $this->search($cidadao,$newProposal,$array4);
            $comissao
                ->loginAs($comission['id'])
                ->visit('/admin/proposals/approved-by-committee')
                ->visit('/admin/proposals/'.$id_proposal.'/bill-project')
                ->type('@number', random_int(1,50))
                ->type('@year', random_int(2019,2021))
                ->type('@owner',$owner_name)
                ->type('@link', $url)
                ->screenshot('7-comission_assigned_proposal')
                ->press('@submit_button-billproject')
                ->pause(2000);
            $cidadao
                ->loginAs($citizen['id'])
                ->visit('proposals/'.$id_proposal)
                ->assertSee('Virou projeto de lei')
                ->screenshot('8-VIROU PROJETO DE LEI');
            $this->search($cidadao,$newProposal,$array4);
        });
    }
}

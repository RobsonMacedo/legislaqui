<?php

namespace Tests\Browser\Pages;

use App\Data\Models\User as User;
use App\Data\Models\Proposal;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Data\Models\State;
use Faker\Generator as Faker;

class SocialLoginTest extends DuskTestCase
{
    public function page($browser){ 
        if ($browser->element('#filter-proposals')){
            
            $browser->visit('/logout')
                ->waitForText('Registrar-se')
                ->screenshot('fim do login twitter com uma verificação');
            }
        
        while ($browser->element('#react-root')){
                
                $browser->type('session[username_or_email]', env('SOCIAL_LOGIN_USER'))
                ->type('session[password]', env('SOCIAL_LOGIN_PASS'))
                ->script('document.querySelector("div[class=\'css-901oao r-1awozwy r-jwli3a r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-b88u0q r-1777fci r-ad9z0x r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\']").click();');
                $browser->waitForText('Quer propor um projeto na Alerj?')
                    ->visit('/logout')
                    ->waitForText('Registrar-se')
                    ->screenshot('fim do login twitter com duas verificações');
                }
            }
    /**
        * @test
        * @group testSocialLoginFacebook
        * @group link
        */

    public function testSocialLoginFacebook()
    {
        $this->browse(function (Browser $browser)
        {
            $browser
                ->visit('/login')
                ->waitForText('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->assertSee('Caso já possua uma conta de usuário')
                ->press('@buttomFacebookLogin')
                ->type('#email', env('SOCIAL_LOGIN_EMAIL'))
                ->type('#pass', env('SOCIAL_LOGIN_PASS'))
                ->press('#loginbutton')
                ->waitForText('Quer propor um projeto na Alerj?')
                ->visit('/logout')
                ->waitForText('Registrar-se')
                ->screenshot('fim do login facebook');
        });
    }

    /**
        * @test
        * @group testSocialLoginTwitter
        * @group link
        */
    

    public function testSocialLoginTwitter()
    {
        $this->browse(function (Browser $browser)
        {
            $browser
                ->visit('/login')
                ->waitForText('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->assertSee('Caso já possua uma conta de usuário')
                ->press('@buttomTwitterLogin')
                ->type('#username_or_email', env('SOCIAL_LOGIN_EMAIL'))
                ->type('#password', env('SOCIAL_LOGIN_PASS'))
                ->press('#allow');
                $this->page($browser);               
        });
    }

    /**
        * @test
        * @group testRegisterCompleteFacebook
        * @group link
        */

    public function testRegisterCompleteFacebook()
    {
        $user = factory(User::class)->raw();
        $ddd = random_int(11,89);
        $whatsapp =  app(Faker::class)->cellphone();
        $this->browse(function (Browser $browser) use ($user, $ddd, $whatsapp)
        {
            $browser
                ->visit('/login')
                ->waitForText('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomFacebookLogin')
                /* ->waitForText('Entrar no Facebook')
                ->type('#email', env('SOCIAL_LOGIN_EMAIL'))
                ->type('#pass', env('SOCIAL_LOGIN_PASS'))
                ->press('#loginbutton') */
                ->waitForText('Quer propor um projeto na Alerj?')
                ->press('@newProposalButton')
                ->waitForText('Complete seu registro')
                ->type('whatsapp', $ddd.$whatsapp)
                ->type('#cpf', $user['cpf'])
                ->select('city_id', random_int(1, 92))
                ->check('#terms')
                ->press('@registerButton')
                ->waitForText('Quer propor um projeto na Alerj?')
                ->press('@newProposalButton')
                ->assertPathIs('/proposals/create')
                ->screenshot('Fim do Cadastro Facebook') 
                ->logout();      
        });
    
    }
    /**
        * @test
        * @group testRegisterCompleteTwitter
        * @group link
        */

    public function testRegisterCompleteTwitter()
    {
        $user = factory(User::class)->raw();
        $ddd = random_int(11,89);
        $whatsapp =  app(Faker::class)->cellphone();
        $this->browse(function (Browser $browser) use ($user, $ddd, $whatsapp)
        {
            $browser
                ->visit('/login')
                ->assertSee('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomTwitterLogin')
                /* ->waitForText('Autorizar o acesso de')
                ->type('#username_or_email', env('SOCIAL_LOGIN_EMAIL'))
                ->type('#password', env('SOCIAL_LOGIN_PASS'))
                ->press('#allow') */
                ->assertSee('Quer propor um projeto na Alerj?')
                ->press('@newProposalButton')
                ->assertSee('Complete seu registro')
                ->type('whatsapp', $ddd.$whatsapp)
                ->type('#cpf', $user['cpf'])
                ->select('city_id', random_int(1, 92))
                ->check('#terms')
                ->press('@registerButton')
                ->assertSee('Quer propor um projeto na Alerj?')
                ->press('@newProposalButton')
                ->assertPathIs('/proposals/create')
                ->screenshot('Fim do Cadastro Twitter');         

        });

    }


} 

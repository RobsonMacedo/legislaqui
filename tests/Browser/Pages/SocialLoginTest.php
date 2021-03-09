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
  
    private static $user;

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
                ->assertSee('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomFacebookLogin')
                ->type('#email', 'testealerj@gmail.com')
                ->type('#pass', 'Alerjteste123')
                ->press('#loginbutton')
                ->waitForText('Quer propor um projeto na Alerj?')
                ->visit('/logout')
                ->waitForText('Registrar-se')
                ->assertSee('Registrar-se')
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
                ->assertSee('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomTwitterLogin')
                ->type('#username_or_email', 'testealerj@gmail.com')
                ->type('#password', 'Alerjteste123')
                ->press('#allow')
                ->pause(2000)
                /* ->type('session[username_or_email]', 'testealerj')
                ->type('session[password]', 'Alerjteste123')
                ->script('$("div[class=\'css-901oao r-1awozwy r-jwli3a r-6koalj r-18u37iz r-16y2uox r-1qd0xha r-a023e6 r-b88u0q r-1777fci r-ad9z0x r-dnmrzs r-bcqeeo r-q4m81j r-qvutc0\']").click();');
                $browser*/->waitForText('Quer propor um projeto na Alerj?')
                ->visit('/logout')
                ->waitForText('Registrar-se')
                ->assertSee('Registrar-se')
                ->screenshot('fim do login twitter');               
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
                ->assertSee('Caso já possua uma conta de usuário, entre com seus dados abaixo')
                ->press('@buttomFacebookLogin')
                /* ->type('#email', 'testealerj@gmail.com')
                ->type('#pass', 'Alerjteste123')
                ->press('#loginbutton') */
                ->pause(2000)
                ->assertSee('Quer propor um projeto na Alerj?')
                ->pause(2000)
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
                /* ->type('#username_or_email', 'testealerj@gmail.com')
                ->type('#password', 'Alerjteste123')
                ->press('#allow') */
                ->pause(2000)
                ->assertSee('Quer propor um projeto na Alerj?')
                ->pause(2000)
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

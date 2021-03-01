<?php

namespace Tests\Browser\Pages;

use App\Repositories\UsersRepository;
use App\Data\Models\User;
use Faker\Generator as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    private static $newUser;
    private static $randomUser;

    public function init()
    {
        static::$newUser = factory(User::class)->raw();
        dd(static::$newUser);
        static::$randomUser = User::all()
            ->random()
            ->toArray();
    }

    public function testRegister()
    {
        $this->init();
        $newUser = static::$newUser;
        $ddd = random_int(11,89);
        $whatsapp =  app(Faker::class)->cellphone();

        $this->browse(function (Browser $browser) use ($newUser,$ddd,$whatsapp) {

            $browser
                ->logout()
                ->visit('/register')
                ->type('name', $newUser['name'])
                ->type('email', $newUser['email'])
                ->type('whatsapp',$ddd.$whatsapp)
                ->type('cpf', $newUser['cpf'])
                //                ->type('@register-password',$newUser['password'])
                ->type('password', '12345678')
                //                ->type('password_confirmation',$newUser['password'])
                ->type('password_confirmation', '12345678')
                ->select('city_id', random_int(1,92))
                ->check('terms')
                ->screenshot('register')
                ->click('@registerButton')
                ->pause(1000)
                ->screenshot('after-register');
        });
        $this->assertDatabaseHas('users', ['email' => $newUser['email']]);
    }

    public function testLogin()
    {
        $this->init();
        $randomUser = static::$randomUser;

        $this->browse(function (Browser $browser) use ($randomUser) {
            $browser
                ->loginAs($randomUser['id'])
                ->visit('/')
                ->pause(1000)
                ->screenshot('login')
                ->assertAuthenticatedAs(
                    app(UsersRepository::class)->findByEmail(
                        $randomUser['email']
                    )
                );
        });
    }
}

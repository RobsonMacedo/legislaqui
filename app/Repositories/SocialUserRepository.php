<?php
/**
 * Created by PhpStorm.
 * User: falbernaz
 * Date: 27/06/2016
 * Time: 10:49.
 */
namespace App\Repositories;


use App\Http\Controllers\Auth\AuthController;
use App\User;
use App\SocialNetwork;
use App\SocialUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Ramsey\Uuid\Uuid;

class SocialUserRepository
{

    private $SocialUser;


     public function find($id)
      {
          $this->SocialUser = new SocialUser();
          return $this->SocialUser->find($id);
      }


      public function destroy($id)
      {
        return User::destroy($id);
      }


    public function createUser($email, $socialUser)
    {
        $userModel = new User;

        if ($socialUser->getName()){
            $userModel-> name = $socialUser->getName();
        } elseif ($socialUser->getNickname()) {
            $userModel-> name = $socialUser->getNickname();
        } else {
            $userModel-> name = 'sem nome';
        }


        if($socialUser->getEmail()){
        } else {
            $userModel-> email= $socialUser->getId() . '@legislaqui.rj.gov.br';
        }

        $userModel-> email = $email;
        $userModel-> password = 'Empty';
        $userModel-> uf = 'RJ';
        $userModel-> role_id = '99';
        $uuid = Uuid::uuid4();
        $userModel-> uuid = $uuid;
        $userModel->save();

        return $userModel;
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers {
        register as traitRegister;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user (citizen - 99) instance after a valid registration.
     *
     * @param array $data
     *
     * @return model user
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uf' => $data['uf'],
            'role_id' => 99,
            'cpf' => $data['cpf'],
            'uuid' => $data['uuid'],
        ]);
    }

    // Register Method Overload
    public function register(Request $request)
    {
        // Request comes from Register form
        \Session::put('last_auth_attempt', 'register');

        if (!app()->environment('local')) {
            // Validates Captcha
            $validate = Validator::make($request->all(), [
                'g-recaptcha-response' => 'required|captcha',
            ]);

            // Verifies if Captcha fails and redirect to register view
            if ($validate->fails()) {
                $error = \Session::flash(
                    'error_msg',
                    'Por favor, clique no campo reCAPTCHA para efetuar o registro!'
                );

                return redirect()
                    ->back()
                    ->withInput($request->all(), 'register')
                    ->withErrors($validate, 'register');
            }
        }

        redirect('auth.login');

        // If Captcha is OK, then register User Request
        $register = $this->traitRegister($request);

        \Session::flash('flash_msg', 'Registro feito com Sucesso.');

        return $register;
    }
}
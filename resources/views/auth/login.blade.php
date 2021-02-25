@extends('layouts.alerj')
@section('content')

    @include('partials.error')
    @if (Session::has('error_msg'))
        <div class="alert alert-danger">{{ Session::get('error_msg') }}</div>
    @endif

    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
        }

        .container-fluid {
            display: none !important;
        }

        .facebook
        {
            background-color: #3B5998;
        }

    </style>



    <div class="d-flex h-100">
        <div class="row align-self-center w-100 login">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header">

                        <h3>
                            Login
                        </h3>

                        <div class="after-auth">
                            <h5>
                                Caso já possua uma conta de usuário, entre com seus dados abaixo.
                            </h5>
                        </div>

                    </div>

                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {!! csrf_field() !!}


                            <div class="form-group{{ $errors->has('email') && (Session::get('last_auth_attempt') === 'login') ? ' has-error' : '' }} row">
                                <div class="col-md-12">
                                    {{--<label for="email" class="col-form-label">Email</label>--}}

                                    @if ($errors->has('email') && (Session::get('last_auth_attempt') === 'login'))
                                        <span class="help-block">
                                            <strong><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    <input id="email" type="email" class="form-control" name="email" value="{{ (Session::get('last_auth_attempt') === 'login') ? old('email') : '' }}" placeholder="Insira seu email">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') && (Session::get('last_auth_attempt') === 'login') ? ' has-error' : '' }} row">
                                {!! csrf_field() !!}
                                <div class="col-md-12">
                                    {{--<label for="password" class="col-form-label">Senha</label>--}}
                                    @if ($errors->has('password') && (Session::get('last_auth_attempt') === 'login'))
                                        <span class="help-block">
                                            <strong><i class="fas fa-exclamation-triangle"></i> {{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif

                                    <input id="password" type="password" class="form-control" name="password" placeholder="Insira sua senha">

                                </div>
                            </div>

                            <div class="form-group row text-left">
                                <div class="col-md-6 ">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            Lembrar de mim
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 text-right">
                                    @if (Route::has('password.request'))
                                        <a class="forgot-pass " href="{{ route('password.request') }}">
                                            Esqueceu a sua senha?
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-md-12">

                                    <button dusk="loginButton" type="submit" class="btn btn-primary btn-block">
                                        <i class="fa fa-btn fa-sign-in fa-fw"></i>Entrar
                                    </button>

                                </div>
                            </div>

                            <div class="form-group row mb-0 text-center">


                                <div class="col-md-12">
                                    - ou faça login via -
                                </div>


                            </div>


                            @foreach(['facebook', 'twitter'] as $provider)
                                <div class="social-login mt-3">
                                    <a class="btn btn-primary btn-block btn-{{$provider}}" dusk='buttomFacebookLogin' href="{{ route('social.login', ['provider' => $provider]) }}">
                                        <span class= 'fab fa-fw fa-{{$provider}}'></span>
                                        Entrar com {{ ucwords($provider) }}
                                    </a>
                                </div>

                            @endforeach
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach

                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@extends('layouts.app')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    @if(session('alert.error'))
                        <div class="alert alert-danger">
                            {{ session('alert.error') }}
                        </div>
                    @endif

                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo">
                            <img src="{{ asset('/images/crms2.png')}}" alt="logo">
                        </div>
                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>
                        <form method="POST" action="{{ route('login') }}" onsubmit='show()'>
                            @csrf   
                            <div class="form-group">
                                <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter Username">
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Enter Password">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                    {{ __('SIGN IN') }}
                                </button>
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                @if (Route::has('password.request'))
                                    <a class="auth-link text-black" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<link rel="stylesheet" type="text/css" href="new_login/fonts/iconic/css/material-design-iconic-font.min.css">
<link rel="stylesheet" type="text/css" href="new_login/css/main.css">

<div class="limiter">
    <div class="container-login100" style="background-image: url('images/Seaweed_farm.jpg');">
        <div class="wrap-login100">
            @if(session('alert.error'))
                <div class="alert alert-danger">
                    {{ session('alert.error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" onsubmit='show()' class="login100-form validate-form">
                @csrf 
                <span class="login100-form-logo">
                    <img src="new_login/images/crms-logo.png" width="100" height="100">
                </span>
                <span class="login100-form-title mt-3 mb-3">Sign in to continue!</span>
                <div class="wrap-input100 validate-input" data-validate = "Enter username">
                    <input id="username" type="username" class="input100{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus placeholder="Enter Username">
                    <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    @if ($errors->has('username'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password">
                    <input id="password" type="password" class="input100{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="Enter Password">
                    <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="container-login100-form-btn mb-2">
                    <button type="submit" class="login100-form-btn auth-form-btn">
                        {{ __('SIGN IN') }}
                    </button>
                </div>

                <div class="text-center p-t-90">
                    @if (Route::has('password.request'))
                        <a class="auth-link text-white" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                    <p class="mt-3"><small class="text-white">Copyright © W Group Inc. 2025</small></p>
                    <hr style="border-top: 1px solid rgb(255 255 255)">
                    <p><small class="text-white">For your system/ technical concerns, click <a href="https://ticketing.rico.com.ph/itd/" target="_blank" style="color: #FFF; font-weigh:600">here</a></small></p>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection

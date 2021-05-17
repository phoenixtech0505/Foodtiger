@extends('layouts.app', ['class' => 'bg'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--6 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                @if (session('status'))
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif


                @if(config('settings.is_demo'))
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body">
                        <div class="text-center text-muted mb-4">
                           <h3>{{ __('DEMO credentials:') }}</h3>

                            <small>
                                <b>{{ __('ADMIN') }}</b><br/>
                                {{ __('Username') }} <strong>admin@example.com</strong><br />
                                {{ __('Password') }} <strong>secret</strong>
                            </small>
                            <small>
                                <br /><br /><b>{{ __('OWNER') }}</b><br/>
                                {{ __('Username') }} <strong>owner@example.com</strong><br />
                                {{ __('Password') }} <strong>secret</strong>
                            </small>
                            @if (config('app.isft'))
                                <small>
                                    <br /><br /><b>{{ __('DRIVER') }}</b><br/>
                                    {{ __('Username') }} <strong>driver@example.com</strong><br />
                                    {{ __('Password') }} <strong>secret</strong>
                                </small>
                                <small>
                                    <br /><br /><b>{{ __('CLIENT') }}</b><br/>
                                    {{ __('Username') }} <strong>client@example.com</strong><br />
                                    {{ __('Password') }} <strong>secret</strong>
                                </small>
                            @endif

                        </div>
                    </div>
                </div>
                @endif
                <br/>

                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">

                        @if(config('app.isft')&&(strlen(config('settings.google_client_id'))>3||strlen(config('settings.facebook_client_id'))>3))
                            <div class="card-header bg-transparent pb-5">
                                <div class="text-muted text-center mt-2 mb-3"><small>{{ __('Sign in with') }}</small></div>
                                <div class="btn-wrapper text-center">

                                    @if (strlen(config('settings.google_client_id'))>3)
                                        <a href="{{ route('google.login') }}" class="btn btn-neutral btn-icon">
                                            <span class="btn-inner--icon"><img src="{{ asset('argonfront/img/google.svg') }}"></span>
                                            <span class="btn-inner--text">Google</span>
                                        </a>
                                    @endif

                                    @if (strlen(config('settings.facebook_client_id'))>3)
                                        <a href="{{ route('facebook.login') }}" class="btn btn-neutral btn-icon">
                                            <span class="btn-inner--icon"><img src="{{ asset('custom/img/facebook.png') }}"></span>
                                            <span class="btn-inner--text">Facebook</span>
                                        </a>
                                    @endif

                                </div>
                            </div>
                        @endif


                        <form role="form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="custom-control custom-control-alternative custom-checkbox">
                                <input class="custom-control-input" name="remember" id="customCheckLogin" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customCheckLogin">
                                    <span class="text-muted">{{ __('Remember me') }}</span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger my-4">{{ __('Sign in') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-light">
                                <small>{{ __('Forgot password?') }}</small>
                            </a>
                        @endif
                    </div>
                    @if(config('app.isft'))
                        <div class="col-6 text-right">
                            <a href="{{ route('register') }}" class="text-light">
                                <small>{{ __('Create new account') }}</small>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

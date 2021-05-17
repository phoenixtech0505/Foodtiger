@extends('layouts.app')
        @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">{{ __('Verify your profile') }}</div>
                        <div class="card-body">
                            <p>{{ __('Thanks for registering with our platform. We will sent you message on your phone number. Provide the code below.') }}</p>

                            <div class="d-flex justify-content-center">
                                <div class="col-8">
                                    <form method="post" action="{{ route('phoneverification.verify') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="code">{{ __('Verification Code') }}</label>
                                            <input id="code" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" type="text" placeholder="{{ __('Enter your verification code')}}" required autofocus>
                                            @if ($errors->has('code'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary">{{ __('Verify profile') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

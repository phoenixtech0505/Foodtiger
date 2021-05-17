@extends('layouts.app', ['title' => __('Restaurant Management')])

@section('content')
    @include('restorants.partials.header', ['title' => __('Add Restaurant')])
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Restaurant Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Restaurant information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('admin.restaurants.store') }}" autocomplete="off">
                                @csrf
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name">{{ __('Restaurant Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Restaurant Name here') }} ..." value="" required autofocus>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                </div>
                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('Owner information') }}</h6>
                                <div class="pl-lg-4">
                                    <div class="form-group{{ $errors->has('name_owner') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="name_owner">{{ __('Owner Name') }}</label>
                                        <input type="text" name="name_owner" id="name_owner" class="form-control form-control-alternative{{ $errors->has('name_owner') ? ' is-invalid' : '' }}"  placeholder="{{ __('Owner Name here') }} ..." value="" required autofocus>
                                        @if ($errors->has('name_owner'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name_owner') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('email_owner') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="email_owner">{{ __('Owner Email') }}</label>
                                        <input type="email" name="email_owner" id="email_owner" class="form-control form-control-alternative{{ $errors->has('email_owner') ? ' is-invalid' : '' }}" placeholder="{{ __('Owner Email here') }} ..." value="" required autofocus>
                                        @if ($errors->has('email_owner'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email_owner') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group{{ $errors->has('phone_owner') ? ' has-danger' : '' }}">
                                        <label class="form-control-label" for="phone_owner">{{ __('Owner Phone') }}</label>
                                        <input type="text" name="phone_owner" id="phone_owner" class="form-control form-control-alternative{{ $errors->has('phone_owner') ? ' is-invalid' : '' }}"  placeholder="{{ __('Owner Phone here') }} ..." value="" required autofocus>
                                        @if ($errors->has('phone_owner'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('phone_owner') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

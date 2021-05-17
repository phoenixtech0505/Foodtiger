@extends('layouts.app', ['title' => __('Clients Management')])

@section('content')
    @include('drivers.partials.header', ['title' => __('Edit Client')])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Client Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="pl-lg-4">
                                <form method="post" action="{{ route('clients.update', $client) }}" autocomplete="off">
                                    @csrf
                                    @method('put')

                                </form>
                                </div>


                                <hr />
                                <h6 class="heading-small text-muted mb-4">{{ __('Client information') }}</h6>
                            <div class="pl-lg-4">


                                <div class="form-group{{ $errors->has('name_client') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="name_client">{{ __('Client Name') }}</label>
                                    <input type="text" name="name_client" id="name_client" class="form-control form-control-alternative" placeholder="{{ __('Client Name') }}" value="{{ old('name', $client->name) }}" readonly>
                                </div>

                                <div class="form-group{{ $errors->has('email_client') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="email_client">{{ __('Client Email') }}</label>
                                    <input type="text" name="email_client" id="email_client" class="form-control form-control-alternative" placeholder="{{ __('Client Email') }}" value="{{ old('name', $client->email) }}" readonly>
                                </div>

                                <div class="form-group{{ $errors->has('phone_client') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="phone_client">{{ __('Client Phone') }}</label>
                                    <input type="text" name="phone_client" id="phone_client" class="form-control form-control-alternative" placeholder="{{ __('Client Phone') }}" value="{{ old('name', $client->phone) }}" readonly>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

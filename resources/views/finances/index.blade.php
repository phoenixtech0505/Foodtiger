@extends('layouts.app', ['title' => __('Orders')])
@section('admin_title')
    {{__('Finances')}}
@endsection
@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--9">
        
        <div class="row">
            @isset($showFeeTerms))
                @include('finances.feeterms')
            @endisset
            @if(config('settings.enable_stripe_connect')&&isset($showStripeConnect)?$showStripeConnect:false)
                @include('finances.stripe')
            @endif
        </div>

        <br />
    
        <!-- Info cards -->
        @isset($cards)
            @include('partials.infoboxes.index') 
        @endisset


        <br />
        <div class="row">
            <div class="col">
                <!-- Order Card -->
                @include('orders.partials.ordercard',['financialReport'=>true])
            </div>
        </div>


    </div>
@endsection
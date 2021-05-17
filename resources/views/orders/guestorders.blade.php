
@extends('layouts.front', ['title' => __('Orders')])

@section('content')
    @include('users.partials.header', ['title' => __(''),])
   

    <div class="container-fluid mt--7"> 
        
        <div class="col-xl-8 offset-xl-2">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-end flex-nowrap">
                        
                        <div class="col-sm-8 align-items-start">
                            <a  href="{{ $backUrl }}" type="button" class="btn btn-primary btn-lg left text-white">
                                <span class="btn-inner--icon"><i class="fa fa-chevron-left"></i></span>
                                <span class="btn-inner--text">{{ __('Go Back')}}</span>
                            </a>
                        </div>
                        <div class="col-sm-4" style=" display: flex; justify-content: flex-end">
                            <a  href="{{ url()->current() }}" type="button" class="btn btn-primary btn-lg align-items-right text-white">
                                <span class="btn-inner--icon"><i class="fa fa-refresh"></i></span>
                                <span class="btn-inner--text">{{ __('Refresh')}}</span>
                            </a>
                        </div>
                        
                        
                        
                    </div>
                </div>
            </div>
        </div>
        <br />
        @foreach ($orders as $order)
            
            <div class="col-xl-8 offset-xl-2">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('Order')." #".$order->id }}</h3>
                        </div>
                    </div>
                    @include('orders.partials.orderstatus')
                    @include('orders.partials.orderinfo')
                </div>
            </div>
            <br />
        @endforeach
        
        


    </div>
@endsection

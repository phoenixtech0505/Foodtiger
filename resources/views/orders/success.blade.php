@extends('layouts.app', ['title' => ''])
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card shadow border-0 mt-8">
            <div class="card-body text-center">
                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                    <span class="swal2-success-line-tip"></span>
                    <span class="swal2-success-line-long"></span>
                    <div class="swal2-success-ring"></div>
                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                </div>
                <h2 class="display-2">{{ __("You're all set!") }}</h2>
                <h1 class="mb-4">
                    <span class="badge badge-primary">{{ __('Order')." #".$order->id }}</span>
                </h1>
                <div class="d-flex justify-content-center">
                    <div class="col-8">
                        <h5 class="mt-0 mb-5 heading-small text-muted">
                            {{ __('Your order is created. You will be notified for further information.' )}}
                        </h5>
                        <div class="font-weight-300 mb-5">{{ __('Thanks for your purchase').", " }}<span class="h3">{{ $order->restorant->name }}</span></div>
                        @if (config('settings.wildcard_domain_ready'))
                            <a href="{{ (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$order->restorant->subdomain.".".str_replace("www.","",$_SERVER['HTTP_HOST']) }}" class="btn btn-outline-primary btn-sm">{{ __('Go back to restaurant') }}</a>
                        @else
                            <a href="{{ route('vendor',$order->restorant->subdomain) }}" class="btn btn-outline-primary btn-sm">{{ __('Go back to restaurant') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection






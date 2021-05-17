<div class="col-12 col-lg-{{$col}}">
    <!-- Card -->
    <div class="card shadow-soft mb-5 mb-lg-6 px-2">
        <div class="card-header border-light py-5 px-4">
            <!-- Price -->
            <div class="d-flex mb-3 text-primary">
                <span class="h5 mb-0"> {{ config('settings.cashier_currency') }}</span>
                <span class="price display-2 mb-0 text-primary" data-annual="0" data-monthly="0">{{ $plan['price'] }}</span>
            <span class="h6 font-weight-normal align-self-end">/{{  $plan['period']==1? __('qrlanding.month') :  __('qrlanding.year') }}</span>
            </div>
        <h4 class="mb-3 text-black">{{  __($plan['name']) }}</h4>
            <p class="font-weight-normal mb-0">{{ __($plan['description']) }}</p>
        </div>
        <div class="card-body pt-5">
            <ul class="list-group simple-list">
                @foreach (explode(",",$plan['features']) as $feature)
                <li class="list-group-item font-weight-normal"><span class="icon-primary"><i class="fas fa-check"></i></span>{{ __($feature) }}</li>
                @endforeach
            </ul>
        </div>
        <div class="card-footer px-4 pb-4">
            <!-- Button -->
            <a href="{{ route('newrestaurant.register') }}" class="btn btn-block btn-outline-gray animate-up-2">
                {{ __('qrlanding.pricing_button') . " "}} {{ $plan['name'] }}<span class="icon icon-xs ml-3"><i class="fas fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</div>

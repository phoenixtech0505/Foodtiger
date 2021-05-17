<div class="card card-profile shadow mt--300">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Checkout') }}<span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <br />
        <div class="alert alert-danger" role="alert">
           {{ __('Order can not be placed since restaurant will be / is closed.')}}
        </div>
      </div>
      <br />
      <br />
    </div>
  </div>
  <br />

  @if(config('settings.is_demo') && config('settings.enable_stripe'))
    @include('cart.democards')    
  @endif

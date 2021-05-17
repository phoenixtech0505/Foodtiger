<!-- Fee Info -->
<div class="col-6">
    <div class="card  bg-secondary shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">{{ __('Stripe connect') }}</h3>
                </div>
                <div class="col-4 text-right">
                </div>
            </div>
        </div>
        <div class="card-body">
         <p>
             {{ __('We use Stripe to collect payments. Connect now, and we will send your funds from cart payments dirrectly to your Stripe account')}}.<br />
             <hr />
             @if (!auth()->user()->stripe_account)
                <a href="{{ route('stripe.connect')}}" class="btn btn-primary">{{ __('Connect with Stripe Connect') }}</a>
             @else

             <div class="row">
                 <div class="col-md-6">
                    <h4>{{__('Stripe account')}}</h4>
                    {{auth()->user()->stripe_account}}
                 </div>
                 <div class="col-md-6">
                    <h4>{{__('Stripe details submited')}}</h4>
                    {{$stripe_details_submitted}}
                 </div>
             </div>
             <br />
             <a href="{{ route('stripe.connect')}}" class="btn btn-warning">{{ __('Update Stripe connection') }}</a>
             @endif
        </p>
        </div>
    </div>
</div>
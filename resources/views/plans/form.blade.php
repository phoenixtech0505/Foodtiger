@include('partials.input',['name'=>'Name','id'=>"name",'placeholder'=>"Plan name",'required'=>true,'value'=>(isset($plan)?$plan->name:null)])
<div class="row">
    <div class="col-md-12">
        @include('partials.input',['name'=>'Plan description','id'=>"description",'placeholder'=>"Plan description...",'required'=>false,'value'=>(isset($plan)?$plan->description:null)])
    </div>
    <div class="col-md-12">
        @include('partials.input',['name'=>'Features list (separate features with comma)','id'=>"features",'placeholder'=>"Plan Features comma separated...",'required'=>false,'value'=>(isset($plan)?$plan->features:null)])
    </div>
</div>

@include('partials.input',['type'=>'number','name'=>'Price','id'=>"price",'placeholder'=>"Plan prce",'required'=>true,'value'=>(isset($plan)?$plan->price:null)])

<div class="row">
    <div class="col-md-6">
        @include('partials.input',['type'=>"number", 'name'=>'Items limit','id'=>"limit_items",'placeholder'=>"Number of items",'required'=>false,'additionalInfo'=>"0 is unlimited numbers of items",'value'=>(isset($plan)?$plan->limit_items:null)])
    </div>
    @if(config('settings.subscription_processor')=='Paddle')
        <div class="col-md-6">
            @include('partials.input',['name'=>'Paddle ID','id'=>"paddle_id",'placeholder'=>"Paddle ID here...",'required'=>false,'value'=>(isset($plan)?$plan->paddle_id:null)])
        </div>
    @endif

    @if(config('settings.subscription_processor')=='Stripe')
        <div class="col-md-6">
            @include('partials.input',['name'=>'Stripe Pricing Plan ID','id'=>"stripe_id",'placeholder'=>"Product price plan id from Stripe starting with price_xxxxxx",'required'=>false,'value'=>(isset($plan)?$plan->stripe_id:null)])
        </div>
    @endif

    @if(config('settings.subscription_processor')=='PayPal')
        <div class="col-md-6">
            @include('partials.input',['name'=>'PayPal Pricing Plan ID','id'=>"paypal_id",'placeholder'=>"Product price plan id from PayPal starting with P-xxxxxx",'required'=>false,'value'=>(isset($plan)?$plan->paypal_id:null)])
        </div>
    @endif

    @if(config('settings.subscription_processor')=='Mollie')
        <div class="col-md-6">
            @include('partials.input',['name'=>'Mollie Pricing Plan ID','id'=>"mollie_id",'placeholder'=>"Product price plan id from Mollie",'required'=>false,'value'=>(isset($plan)?$plan->mollie_id:null)])
        </div>
    @endif

    @if(config('settings.subscription_processor')=='Paystack')
        <div class="col-md-6">
            @include('partials.input',['name'=>'Paystack Pricing Plan ID','id'=>"paystack_id",'placeholder'=>"Product price plan id from Paystack",'required'=>false,'value'=>(isset($plan)?$plan->paystack_id:null)])
        </div>
    @endif

</div>

<br/>
<div class="row">
<!-- THIS IS SPECIAL -->
<div class="col-md-6">
    <label class="form-control-label">{{ __("Plan period") }}</label>
    <div class="custom-control custom-radio mb-3">
        <input name="period" class="custom-control-input" id="monthly"  @if (isset($plan))  @if ($plan->period == 1) checked @endif @else checked @endif  value="monthly" type="radio">
        <label class="custom-control-label" for="monthly">{{ __('Monthly') }}</label>
    </div>
    <div class="custom-control custom-radio mb-3">
        <input name="period" class="custom-control-input" id="anually" value="anually" @if (isset($plan) && $plan->period == 2) checked @endif type="radio">
        <label class="custom-control-label" for="anually">{{ __('Anually') }}</label>
    </div>
</div>


<!-- ORDERS -->
<div class="col-md-6">
    <label class="form-control-label">{{ __("Ordering") }}</label>
    <div class="custom-control custom-radio mb-3">
        <input name="ordering" class="custom-control-input" id="enabled" value="enabled"  @if (isset($plan))  @if ($plan->enable_ordering == 1) checked @endif @else checked @endif  type="radio">
        <label class="custom-control-label" for="enabled">{{ __('Enabled') }}</label>
    </div>
    <div class="custom-control custom-radio mb-3">
        <input name="ordering" class="custom-control-input" id="disabled" value="disabled" @if (isset($plan) && $plan->enable_ordering == 2) checked @endif type="radio">
        <label class="custom-control-label" for="disabled">{{ __('Disabled') }}</label>
    </div>
</div>
</div>
<br/>



<div class="text-center">
    <button type="submit" class="btn btn-success mt-4">{{ isset($plan)?__('Update plan'):__('SAVE') }}</button>
</div>

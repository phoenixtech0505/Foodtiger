<div class="card card-profile shadow">
    <div class="px-4">
        <div class="mt-5 text-center">
            <h3>{{ __('HAVE A PROMO CODE?') }}<span class="font-weight-light"></span></h3>
        </div>
        <div class="card-content border-top">
        <br/>
        <div class="col-md-10 offset-md-1">
            <div class="form-group">
                <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="{{ __('Enter your promo code here') }}">
                <small class="text-muted"><strong>{{ __('Only one promo code may be user per order') }}</strong></small>
            </div>
        </div>
        <br/>
        <div class="text-center">
            <button type="button" id="promo_code_btn" class="btn btn-primary btn-sm">{{ __('Apply') }}</button>
            <span><i id="promo_code_succ" class="ni ni-check-bold text-success"></i></span>
            <span><i id="promo_code_war" class="ni ni-fat-remove text-danger"></i></span>
        </div>
        <br/>
        </div>
    </div>
</div>
<br/>
<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-danger btn-sm">{{ __('Cancel Order') }}</a>
</div>
<br/>

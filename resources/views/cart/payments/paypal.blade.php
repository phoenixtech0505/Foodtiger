<div class="text-center" id="paypal-payment-form" style="display: {{ config('settings.default_payment')=="paypal"?"block":"none"}};" >
        <button
            v-if="totalPrice"
            type="submit"
            class="btn btn-success mt-4 paymentbutton"
            onclick="this.disabled=true;this.form.submit();"
        >{{ __('Place order') }}</button>
    </div>



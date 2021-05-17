<div class="text-center" id="paystack-payment-form" style="display: {{ config('settings.default_payment')=="paystack"?"block":"none"}};" >
    <button
        v-if="totalPrice"
        type="submit"
        class="btn btn-success mt-4 paymentbutton"
        onclick="this.disabled=true;this.form.submit();"
    >{{ __('Place order') }}</button>
</div>

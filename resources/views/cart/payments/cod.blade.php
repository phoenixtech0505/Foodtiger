@if(!config('settings.hide_cod'))
    <div class="text-center" id="totalSubmitCOD"  style="display: {{ config('settings.default_payment')=="cod"&&!config('settings.hide_cod')?"block":"none"}};" >
        <button
            v-if="totalPrice"
            type="submit"
            class="btn btn-success mt-4 paymentbutton"
            onclick="this.disabled=true;this.form.submit();"
        >{{ __('Place order') }}</button>
    </div>
@endif

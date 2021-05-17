<div class="text-center" id="totalSubmitCOD">
    <button 
        
        v-if="totalPrice"
        type="submit"
        class="btn btn-lg btn-icon btn-success mt-4 paymentbutton"
        onclick="this.disabled=true;this.form.submit();"
    >
    <span class="btn-inner--icon lg"><i class="fa fa-whatsapp" aria-hidden="true"></i></span>
    <span class="btn-inner--text">{{ __('Send Whatsapp Order') }}</span>
</div>


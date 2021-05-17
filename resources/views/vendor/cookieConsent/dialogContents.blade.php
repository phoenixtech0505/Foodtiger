<div id="allow_cookies" class="cookieConsentContainer js-cookie-consent cookie-consent">

    <h2>
        {{ __('qrlanding.cookies') }}
    </h2>
    <span class="cookie-consent__message cookieTitle">
        {!! trans('cookieConsent::texts.message') !!}
    </span>
    <br /><br />
    <button class="btn btn-white js-cookie-consent-agree cookie-consent__agree">
        {{ trans('cookieConsent::texts.agree') }}
    </button>

</div>

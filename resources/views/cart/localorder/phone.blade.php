<div class="card card-profile shadow" id="localorder_phone">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Phone') }}<span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
            <input type="text" name="phone" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="{{ __( 'Your phone here' ) }} ..." required></input>
            @if ($errors->has('phone'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
        </div>
      </div>
      <br />
      <br />
    </div>
</div>
<br />

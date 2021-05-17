<div class="card card-profile shadow">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Restaurant information') }}<span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <div class="pl-lg-4">
            <p>
                {{ $restorant->name }}<br />
                {{ $restorant->address }}<br />
                {{ $restorant->phone }}<br />
            </p>
            @if(!empty($openingTime) && !empty($closingTime))
                <p>{{ __('Today working hours') }}: {{ $openingTime . " - " . $closingTime }}</p>
            @endif
      </div>
      </div>
      <br />
      <br />
    </div>
  </div>
  <br />

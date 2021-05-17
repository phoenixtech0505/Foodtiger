<div class="card card-profile shadow" id="addressBox">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Delivery Address') }}<span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        @include('partials.input',['ftype'=>'input','name'=>"",'id'=>"addressID",'placeholder'=>"Your delivery address here ...",'required'=>true])
      </div>
      <br />
      <br />
    </div>
</div>

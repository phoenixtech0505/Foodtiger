@extends('layouts.front', ['class' => ''])
@section('content')
    <section class="section-profile-cover section-shaped my--1 d-none d-md-none d-lg-block d-lx-block">
        <!-- Circles background -->
        <img class="bg-image " src="{{ config('global.restorant_details_cover_image') }}" style="width: 100%;">
        <!-- SVG separator -->
        <div class="separator separator-bottom separator-skew">

        </div>
    </section>
    <section class="section bg-secondary">

      <div class="container">


        <x:notify-messages />

          <div class="row">

            <!-- Left part -->
            <div class="col-md-7">

              <!-- List of items -->
              @include('cart.items')

                <form id="order-form" role="form" method="post" action="{{route('order.store')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @if(!config('settings.social_mode'))

                    @if (config('app.isft')&&count($timeSlots)>0)
                    <!-- FOOD TIGER -->
                        <!-- Delivery method -->
                        @if($restorant->can_pickup == 1)
                            @if($restorant->can_deliver == 1)
                              @include('cart.delivery')
                            @endif
                        @endif

                        <!-- Delivery time slot -->
                        @include('cart.time')

                        <!-- Delivery address -->
                        <div id='addressBox'>
                            @include('cart.address')
                        </div>

                        <!-- Comment -->
                        @include('cart.comment')

                    @else

                      <!-- QRSAAS -->
                      
                      <!-- DINE IN OR TAKEAWAY -->
                      @if (config('settings.enable_pickup'))
                          @include('cart.localorder.dineiintakeaway')
                          <!-- Takeaway time slot -->
                          <div class="takeaway_picker" style="display: none">
                              @include('cart.time')
                          </div>
                      @endif

                      <!-- LOCAL ORDERING -->
                      @include('cart.localorder.table')


                      <!-- Local Order Phone -->
                      @include('cart.localorder.phone')

                      <!-- Comment -->
                      @include('cart.comment')
                        

                    @endif
                @else
                    <!-- Social MODE -->

                    @if(count($timeSlots)>0)
                        <!-- Delivery method -->
                        @include('cart.delivery')

                        <!-- Delivery time slot -->
                        @include('cart.time')

                        <!-- Delivery adress -->
                        @include('cart.newaddress')

                        <!-- Comment -->
                        @include('cart.comment')
                    @endif
                @endif

              <!-- Restaurant -->
              @include('cart.restaurant')
            </div>


          <!-- Right Part -->
          <div class="col-md-5">

            @if (count($timeSlots)>0||config('app.isqrsaas'))
                <!-- Payment -->
                @include('cart.payment')
                <!--
                  <br/>
                  @include('cart.coupons')
                -->
            @else
                <!-- Closed restaurant -->
                @include('cart.closed')
            @endif


          </div>
        </div>


    </div>
    @include('clients.modals')
  </section>
@endsection
@section('js')

  <script async defer src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initAddressMap"></script>
  <!-- Stripe -->
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    "use strict";
    var RESTORANT = <?php echo json_encode($restorant) ?>;
    var STRIPE_KEY="{{ config('settings.stripe_key') }}";
    var ENABLE_STRIPE="{{ config('settings.enable_stripe') }}";
    var initialOrderType = 'delivery';
    if(RESTORANT.can_deliver == 1 && RESTORANT.can_pickup == 0){
        initialOrderType = 'delivery';
    }else if(RESTORANT.can_deliver == 0 && RESTORANT.can_pickup == 1){
        initialOrderType = 'pickup';
    }
  </script>
  <script src="{{ asset('custom') }}/js/checkout.js"></script>
@endsection


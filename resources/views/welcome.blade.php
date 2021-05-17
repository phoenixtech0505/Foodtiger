@extends('layouts.front', ['class' => ''])

@section('content')
    @if( !request()->get('location') )
        @include('layouts.headers.search')
    @else
        @include('layouts.headers.filters')
    @endif



    @foreach ($sections as $section)

        <section class="section" id="main-content">
            <div class="container mt--100">
                <h2>{{ $section['title'] }}</h2>
                @isset($section['super_title'])
                    <h2 class="super_title">{{ $section['super_title'] }}</h2>
                @endisset

                <br />
                <div class="row">
                    <!-- Stores -->
                    @isset($section['restorants'])
                        @forelse ($section['restorants'] as $restorant)
                            <?php $link=route('vendor',['alias'=>$restorant->alias]); ?>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <div class="strip">
                                    <figure>
                                    <a href="{{ $link }}"><img src="{{ $restorant->logom }}" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                                    </figure>
                                    <span class="res_title"><b><a href="{{ $link }}">{{ $restorant->name}}</a></b></span><span class="float-right"><i class="fa fa-star" style="color: #dc3545"></i> <strong>{{ number_format($restorant->averageRating, 1, '.', ',') }} <span class="small">/ 5 ({{ count($restorant->ratings) }})</strong></span></span><br />
                                    <span class="res_description">{{ $restorant->description}}</span><br />
                                    <span class="res_mimimum">{{ __('Minimum order') }}: @money($restorant->minimum, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                            </div>
                            @empty
                            <div class="col-md-12">
                            <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                            </div>

                        @endforelse
                    @endisset


                    <!-- Cities -->
                    @isset($section['cities'])
                        @forelse (isset($section['cities'])?$section['cities']:[] as $city)
                            <?php $link=route('show.stores',['city'=>$city->alias]); ?>
                            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                <div class="strip strip_city">
                                    <figure>

                                        <a href="{{ $link }}"><img src="{{ $city->logo }}" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>

                                        <span class="city_title mt--1"><b><a class="text-white" href="{{ $link }}">{{ $city->name}}</a></b></span><br />
                                        <a href="{{ $link }}" class="city_letter mt--1 text-red fade-in">{{ $city->alias}}</a>
                                    </figure>

                                </div>
                            </div>
                            @empty
                            <div class="col-md-12">
                            <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                            </div>

                        @endforelse
                    @endisset

                </div>



            </div>
        </section>
    @endforeach


    @if(config('global.playstore') || config('global.appstore'))
    <hr>
    <section class="row row-grid align-items-center mt section" style="  ">
        <div class="container py-md">
            <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="">{{ __(config('global.mobile_info_title')) }}</h2>
                <h4 class="mb-0 font-weight-light">{{ __(config('global.mobile_info_subtitle')) }}</h4>
            </div>
            <div class="col-lg-6 text-lg-center btn-wrapper">
                <div class="row">
                    @if(config('global.playstore'))
                    <div class="col-6">
                        <a href="{{config('global.playstore')}}" target="_blank"><img class="img-fluid" src="/default/playstore.png" alt="..."/></a>
                    </div>
                    @endif
                    @if(config('global.appstore'))
                    <div class="col-6">
                        <a href="{{config('global.appstore')}}" target="_blank"><img class="img-fluid" src="/default/appstore.png" alt="..."/></a>
                    </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </section>
@endif

@endsection

@section('js')
    <script>
    "use strict";
    var IsplaceChange = false;
    $(document).ready(function () {
        var input = document.getElementById('txtlocation');

        $("#txtlocation").keydown(function () {
            IsplaceChange = false;
        });

        $("#btnsubmit").on("click",function () {

            if (IsplaceChange == false) {
                $("#txtlocation").val('');
                alert("please Enter valid location");
            }
            else {
                alert($("#txtlocation").val());
            }

        });

        $(".btn_delivery_pickup").click(function() {
            var value = $(this).attr('id');
            $('#expedition').val(value);
        });

        $("#expedition_toggle").on('change', function() {
            var value;
            if ($(this).is(':checked')) { value = "pickup" } else { value = "delivery" }

            $('#expedition').val(value);
            document.getElementById('theQuryForm').submit();
        });

        $('#blogCarousel').carousel({
            interval: 5000
        });

        $("#search_location").on("click",function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type:'POST',
                        url: '/search/location',
                        dataType: 'json',
                        data: {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        },
                        success:function(response){
                            if(response.status){
                                $("#txtlocation").val(response.data.formatted_address);
                            }
                        }, error: function (response) {
                        }
                    })
                    }, function() {

                    });
                } else {
                // Browser doesn't support Geolocation
                //handleLocationError(false, infoWindow, map.getCenter());
            }
        });
    });
</script>
@endsection

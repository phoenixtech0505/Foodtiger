@extends('layouts.app', ['title' => __('My Addresses')])

@section('content')
    @include('addresses.partials.modals')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('My Addresses') }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    @if(count($addresses))
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Address') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($addresses as $address)
                                    <tr>
                                        <td>{{$address->address}}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="card-footer py-4">
                        @if(count($addresses))
                            <nav class="d-flex justify-content-end" aria-label="...">
                            </nav>
                        @else
                            <h4>{{ __('You don`t have any addresses') }} ...</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@section('js')
    <script async defer
        src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initMapA">
    </script>
    <script type="text/javascript">
        "use strict";
        var map, infoWindow, marker, lng, lat;
        function initMapA() {
            map = new google.maps.Map(document.getElementById('map2'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
            marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
            infoWindow = new google.maps.InfoWindow;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

                    map.setCenter(pos);
                    marker.setPosition(pos);
                    //changeLocation(pos.lat, pos.lng);
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;
                }, function() {
                            // handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                            // Browser doesn't support Geolocation
                            //handleLocationError(false, infoWindow, map.getCenter());
            }

            map.addListener('click', function(event) {
                var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
                marker.setPosition(currPos);
                //changeLocation(event.latLng.lat(), event.latLng.lng());

                lat = event.latLng.lat()
                lng = event.latLng.lng();
            });
        }

        $("#submitNewAddress").on("click",function() {
            saveLocation(lat, lng);
        });

        function saveLocation(lat, lng){
            var new_address = $('#new_address').val();

            if(new_address.length > 0){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type:'POST',
                    url: '/addresses',
                    dataType: 'json',
                    data: {
                        new_address: new_address,
                        lat: lat,
                        lng: lng
                    },
                    success:function(response){
                        if(response.status){
                            window.location.href = "/addresses";
                        }
                    }, error: function (response) {
                    //alert(response.responseJSON.errMsg);
                    }
                })
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
@endsection

@extends('layouts.app', ['title' => __('Orders')])
@section('admin_title')
    {{__('Restaurant Management')}}
@endsection
@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-6">
                <br/>
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Restaurant Management') }}</h3>
                                @if (config('settings.wildcard_domain_ready'))
                                    <span class="blockquote-footer">{{ (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$restorant->subdomain.".".str_replace("www.","",$_SERVER['HTTP_HOST']) }}</span>
                                @endif
                            </div>
                            <div class="col-4 text-right">
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                @endif

                                @if (config('settings.wildcard_domain_ready'))
                                    <a target="_blank" href="{{ (isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] ?"https://":"http://").$restorant->subdomain.".".str_replace("www.","",$_SERVER['HTTP_HOST']) }}" class="btn btn-sm btn-success">{{ __('View it') }}</a>
                                @else
                                    <a target="_blank" href="{{ route('vendor',$restorant->subdomain) }}" class="btn btn-sm btn-success">{{ __('View it') }}</a>
                                @endif

                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                       <h6 class="heading-small text-muted mb-4">{{ __('Restaurant information') }}</h6>
                        @include('partials.flash')
                        @include('restorants.partials.info')
                            <hr />
                            @include('restorants.partials.owner')
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-5 mb-xl-0">
                    <br/>
                    @include('restorants.partials.location')
                    <br/>
                    @include('restorants.partials.hours')

                @if(auth()->user()->hasRole('admin')&&config('app.isqrsaas'))
                    <br />
                    @include('restorants.partials.plan')
                @endif
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@section('js')
<!-- Google Map -->
<script async defer src= "https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=<?php echo config('settings.google_maps_api_key'); ?>"> </script>

    <script type="text/javascript">
        "use strict";
        var defaultHourFrom = "09:00";
        var defaultHourTo = "17:00";

        var timeFormat = '{{ config('settings.time_format') }}';

        function formatAMPM(date) {
            //var hours = date.getHours();
            //var minutes = date.getMinutes();
            var hours = date.split(':')[0];
            var minutes = date.split(':')[1];

            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            //minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return strTime;
        }

        //console.log(formatAMPM("19:05"));

        var config = {
            enableTime: true,
            dateFormat: timeFormat == "AM/PM" ? "h:i K": "H:i",
            noCalendar: true,
            altFormat: timeFormat == "AM/PM" ? "h:i K" : "H:i",
            altInput: true,
            allowInput: true,
            time_24hr: timeFormat == "AM/PM" ? false : true,
            onChange: [
                function(selectedDates, dateStr, instance){
                    //...
                    this._selDateStr = dateStr;
                },
            ],
            onClose: [
                function(selDates, dateStr, instance){
                    if (this.config.allowInput && this._input.value && this._input.value !== this._selDateStr) {
                        this.setDate(this.altInput.value, false);
                    }
                }
            ]
        };

        $("input[type='checkbox'][name='days']").change(function() {


            var hourFrom = flatpickr($('#'+ this.value + '_from'), config);
            var hourTo = flatpickr($('#'+ this.value + '_to'), config);

            if(this.checked){
                hourFrom.setDate(timeFormat == "AP/PM" ? formatAMPM(defaultHourFrom) : defaultHourFrom, false);
                hourTo.setDate(timeFormat == "AP/PM" ? formatAMPM(defaultHourTo) : defaultHourTo, false);
            }else{
                hourFrom.clear();
                hourTo.clear();
            }
        });

        $('input:radio[name="primer"]').change(function(){
            if($(this).val() == 'map') {
                $("#clear_area").hide();
            }else if($(this).val() == 'area' && isClosed){
                $("#clear_area").show();
            }
        });

        $("#clear_area").on("click",function() {
            //remove markers
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }

            //remove polygon
            poly.setMap(null);
            poly.setPath([]);

            poly = new google.maps.Polyline({ map: map_area, path: [], strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });

            path = poly.getPath();

            //update delivery path
            changeDeliveryArea(getLatLngFromPoly(path))

            isClosed = false;
            $("#clear_area").hide();
        });

        //Initialize working hours
        function initializeWorkingHours(){
            var workingHours = {!! json_encode($hours) !!};
            if(workingHours != null){
                Object.keys(workingHours).map((key, index)=>{
                    if(workingHours[key] != null){
                        var hour = flatpickr($('#'+key), config);
                        hour.setDate(workingHours[key], false);

                        var day_key = key.split('_')[0];
                        $('#day'+day_key).attr('checked', 'checked');
                    }
                })
            }
        }

        function getLocation(callback){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'GET',
                url: '/get/rlocation/'+$('#rid').val(),
                success:function(response){
                    if(response.status){
                        return callback(true, response.data)
                    }
                }, error: function (response) {
                return callback(false, response.responseJSON.errMsg);
                }
            })
        }

        function changeLocation(lat, lng){
            //var latConv = parseFloat(lat.toString().substr(0, 5));
            //var lngConv = parseFloat(lng.toString().substr(0, 5));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: '/updateres/location/'+$('#rid').val(),
                dataType: 'json',
                data: {
                    lat: lat,
                    lng: lng
                },
                success:function(response){
                    if(response.status){
                        console.log(response.status)
                    }
                }, error: function (response) {
                //alert(response.responseJSON.errMsg);
                }
            })
        }

        function changeDeliveryArea(path){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url: '/updateres/delivery/'+$('#rid').val(),
                dataType: 'json',
                data: {
                    //path: JSON.stringify(path.i)
                    path: JSON.stringify(path)
                },
                success:function(response){
                    if(response.status){
                        console.log(response.status)
                    }
                }, error: function (response) {
                //alert(response.responseJSON.errMsg);
                }
            })
        }

        function initializeMap(lat, lng){
            var map_options = {
                zoom: 13,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: "terrain",
                scaleControl: true
            }

            map_location = new google.maps.Map( document.getElementById("map_location"), map_options );
            map_area = new google.maps.Map( document.getElementById("map_area"), map_options );
        }

        function initializeMarker(lat, lng){
            var markerData = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                position: markerData,
                map: map_location,
                icon: start
            });
        }

        function getLatLngFromPoly(path){
            //get lat long from polygon
            var polygonBounds = path;
            var bounds = [];
            for (var i = 0; i < polygonBounds.length; i++) {
                var point = {
                    lat: polygonBounds.getAt(i).lat(),
                    lng: polygonBounds.getAt(i).lng()
                };

                bounds.push(point);
            }

            return bounds;
        }

        function new_delivery_area(latLng){
            if (isClosed) return;
            markerIndex = poly.getPath().length;
            var isFirstMarker = markerIndex === 0;
            markerArea = new google.maps.Marker({ map: map_area, position: latLng, draggable: false, icon: area });

            //push markers
            markers.push(markerArea);

            if(isFirstMarker) {
                google.maps.event.addListener(markerArea, 'click', function () {
                    if (isClosed) return;
                    path = poly.getPath();
                    poly.setMap(null);
                    poly = new google.maps.Polygon({ map: map_area, path: path, strokeColor: "#FF0000", strokeOpacity: 0.8, strokeWeight: 2, fillColor: "#FF0000", fillOpacity: 0.35, editable: false });
                    isClosed = true;

                    //update delivery path
                    changeDeliveryArea(getLatLngFromPoly(path));
                    //show button clear
                    //$("#clear_area").show();
                });
            }
            //show button clear
            $("#clear_area").show();

            google.maps.event.addListener(markerArea, 'drag', function (dragEvent) {
                poly.getPath().setAt(markerIndex, dragEvent.latLng);
            });
            poly.getPath().push(latLng);
        }

        function initialize_existing_area(area_positions){
            for(var i=0; i<area_positions.length; i++){
                var markerAreaData = new google.maps.LatLng(area_positions[i].lat, area_positions[i].lng);
                markerArea = new google.maps.Marker({ map: map_area, position: markerAreaData, draggable: false, icon: area });

                //push markers
                markers.push(markerArea);

                //var path = poly.getPath();
                path = poly.getPath();

                poly.setMap(null);
                poly = new google.maps.Polygon({ map: map_area, path: path, strokeColor: "#FF0000", strokeOpacity: 0.8, strokeWeight: 2, fillColor: "#FF0000", fillOpacity: 0.35, editable: false });

                //show clear area
                isClosed = true;
                $("#clear_area").show();
                //google.maps.event.addListener(markerArea, "drag", update_polygon_closure(poly, i));
            }


        }

        var start = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Pink.png"
        var area = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Chartreuse.png"
        var map_location = null;
        var map_area = null;
        var marker = null;
        var infoWindow = null;
        var lat = null;
        var lng = null;
        var circle = null;
        var isClosed = false;
        var poly = null;
        var markers = [];
        var markerArea = null;
        var markerIndex = null;
        var path = null;

        window.onload = function () {
            //var map, infoWindow, marker, lng, lat;

            //Working hours
            initializeWorkingHours();

            getLocation(function(isFetched, currPost){
                if(isFetched){
                    infoWindow = new google.maps.InfoWindow;

                    if(currPost.lat != 0 && currPost.lng != 0){
                        //initialize map
                        initializeMap(currPost.lat, currPost.lng)

                        //initialize marker
                        initializeMarker(currPost.lat, currPost.lng)

                        //var isClosed = false;

                        poly = new google.maps.Polyline({ map: map_area, path: currPost.area ? currPost.area : [], strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });

                        if(currPost.area != null){
                            initialize_existing_area(currPost.area)
                        }

                        map_location.addListener('click', function(event) {
                            marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                            changeLocation(event.latLng.lat(), event.latLng.lng());
                        });

                        map_area.addListener('click', function(event) {
                            new_delivery_area(event.latLng)
                        });
                    }else{
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

                                //infoWindow.setPosition(pos);
                                //infoWindow.setContent('Location found.');
                                //infoWindow.open(map);

                                //initialize map
                                initializeMap(position.coords.latitude, position.coords.longitude)

                                //initialize marker
                                initializeMarker(position.coords.latitude, position.coords.longitude)

                                //change location in database
                                changeLocation(pos.lat, pos.lng);

                                map_location.addListener('click', function(event) {
                                    marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                                    changeLocation(event.latLng.lat(), event.latLng.lng());
                                });

                                map_area.addListener('click', function(event) {
                                    new_delivery_area(event.latLng)
                                });
                            }, function() {
                                // handleLocationError(true, infoWindow, map.getCenter());

                                //initialize map
                                initializeMap(54.5260, 15.2551)

                                //initialize marker
                                initializeMarker(54.5260, 15.2551)

                                map_location.addListener('click', function(event) {
                                    marker.setPosition(new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()));

                                    changeLocation(event.latLng.lat(), event.latLng.lng());
                                });

                                map_area.addListener('click', function(event) {
                                    new_delivery_area(event.latLng)
                                });
                            });
                        } else {
                            // Browser doesn't support Geolocation
                            //handleLocationError(false, infoWindow, map.getCenter());
                        }
                    }
                }
            });
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
@endsection


    "use strict";
    //Address map checkout
    var start = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Pink.png"
    var map = null;
    var markerData = null;
    var marker = null;
    var latAdd = null;
    var lngAdd = null;

    $("#new_address_map").hide();
    $("#new_address_spinner").hide();
    $("#address-info").hide();
    $("#submitNewAddress").hide();

    $('#address_number').val('');
    $('#number_apartment').val('');
    $('#number_intercom').val('');
    $('#floor').val('');
    $('#entry').val('');

    function getPlaceDetails(place_id, callback){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/new/address/details',
            data: { place_id: place_id },
            success:function(response){
                if(response.status){
                    return callback(true, response.result)
                }
            }, error: function (response) {
             //return callback(false, response.responseJSON.errMsg);
            }
        })
    }

    $("#modal-order-new-address").on("hidden.bs.modal", function () {
        $("#new_address_spinner").hide();
        $("#new_address_map").hide();
        $('#new_address_checkout').empty();
        $("#address-info").hide();
        $("#submitNewAddress").hide();
    })

    $('select[id="new_address_checkout"]').change(function(){
        $('#address_number').val('');
        $('#number_apartment').val('');
        $('#number_intercom').val('');
        $('#floor').val('');
        $('#entry').val('');

        var place_id = $("#new_address_checkout option:selected").val();
        $("#new_address_spinner").show();

        getPlaceDetails(place_id, function(isFetched, data){
            if(isFetched){

                $("#new_address_spinner").hide();
                $("#new_address_map").show();
                $("#address-info").show();
                $("#submitNewAddress").show();

                var latAdd = data.lat;
                var lngAdd = data.lng;

                map = new google.maps.Map(document.getElementById('new_address_map'), {
                    zoom: 17,
                    center: new google.maps.LatLng(data.lat, data.lng)
                });

                var markerData = new google.maps.LatLng(data.lat, data.lng);
                marker = new google.maps.Marker({
                    position: markerData,
                    map: map,
                    icon: start,
                    title: data.name
                });

                map.addListener('click', function(event) {
                    var data = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                    marker.setPosition(data);

                    var latAdd = event.latLng.lat();
                    var lngAdd = event.latLng.lng();
                });
            }
        });
    })

    $("#submitNewAddress").on("click",function() {
        var address_name = $("#new_address_checkout option:selected").text();
        var address_number = $("#address_number").val();
        var number_apartment = $("#number_apartment").val();
        var number_intercom = $("#number_intercom").val();
        var entry = $("#entry").val();
        var floor = $("#floor").val();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/addresses',
                data: {
                    new_address: address_number.length != 0 ? address_number + ", " + address_name : address_name,
                    lat: latAdd,
                    lng: lngAdd,
                    apartment: number_apartment,
                    intercom: number_intercom,
                    entry: entry,
                    floor: floor
                },
                success:function(response){
                    if(response.status){
                        location.replace(response.success_url);
                    }
                }, error: function (response) {
                    //return callback(false, response.responseJSON.errMsg);
                }
            })
    });


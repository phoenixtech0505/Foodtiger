"use strict";
console.log("Checkout JS includeded");
window.onload?window.onload():console.log("No other windowonload foound");
window.onload = function () {
    checkPrivacyPolicy();
    initAddress();
    initCOD();
    //getUserAddresses();

    if(ENABLE_STRIPE){
        initStripePayment();
    }
}

var checkPrivacyPolicy = function(){
    if (!$('#privacypolicy').is(':checked')) {

        $('.paymentbutton').attr("disabled", true);
    }
}

$("#privacypolicy").change(function() {
    if(this.checked) {
        $('.paymentbutton').attr("disabled", false);
    }else{
        $('.paymentbutton').attr("disabled", true);
    }
});

var validateAddressInArea = function(positions, area){
    var paths = [];

    if(area !== null){
        area.forEach(location =>
            paths.push(new google.maps.LatLng(location.lat, location.lng))
        );
    }
    var delivery_area = new google.maps.Polygon({ paths: paths });

    if(area != null){
        Object.keys(positions).map(function(key, index) {
            //alert("OK")
            setTimeout(function() {
                var belongsToArea = google.maps.geometry.poly.containsLocation(new google.maps.LatLng(positions[key].lat, positions[key].lng), delivery_area);

                if(belongsToArea === false){
                    $('#address'+key).attr('disabled', 'disabled');
                }
            }, 100);
        });
    }
}




//JS FORM Validate functions
var validateOrderFormSubmit=function(){
    var deliveryMethod=$('input[name="deliveryType"]:checked').val();

    //If deliverty, we need to have selected address
    if(deliveryMethod=="delivery"){
        //console.log($("#addressID").val())
        if ($("#addressID").val()) {
            return true;
        }else{
            alert("Please select address");
            return false;
        }
    }else{
        return true;
    }
}

var initCOD=function(){
    console.log("Initialize COD");
     // Handle form submission  - for card.
     var form = document.getElementById('order-form');
     form.addEventListener('submit', async function(event) {
         event.preventDefault();
         console.log('prevented');
         //IF delivery - we need to have selected address
         if(validateOrderFormSubmit()){
            console.log('Form valid');
            form.submit();
         }
    });
}

/**
 *
 * Payment Functions
 *
 */
var initStripePayment=function(){

    console.log("Payment initialzing");

    //On select payment method
    $('input:radio[name="paymentType"]').change(

        function(){
            //HIDE ALL
            $('#totalSubmitCOD').hide()
            $('#totalSubmitStripe').hide()
            $('#stripe-payment-form').hide()

            if($(this).val()=="cod"){
                //SHOW COD
                $('#totalSubmitCOD').show();
            }else if($(this).val()=="stripe"){
                //SHOW STRIPE
                $('#totalSubmitStripe').show();
                $('#stripe-payment-form').show()
            }
        }
    );

     // Create a Stripe client.
     var stripe = Stripe(STRIPE_KEY);

     // Create an instance of Elements.
     var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
            color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    var options = {
        // Custom styling can be passed to options when creating an Element.
        style: {
            base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: '#32325d',
            padding: '2px 2px 4px 2px',
            },
        }
    }

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    const cardHolderName = document.getElementById('name');

    // Handle form submission  - for card.
    var form = document.getElementById('stripe-payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        //IF delivery - we need to have selected address
        if(validateOrderFormSubmit()){
            const { paymentMethod, error } = await stripe.createPaymentMethod(
                'card', card, {
                    billing_details: { name: cardHolderName.value }
                }
            );

            if (error) {
                // Display "error.message" to the user...
                alert(error.message);
            } else {
                stripePaymentMethodHandler(paymentMethod.id);
            }
        }



    });

    // Submit the form with the payment ID.
    function stripePaymentMethodHandler(payment_id) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('order-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripePaymentId');
        hiddenInput.setAttribute('value', payment_id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
}

/**
 *
 * Address Functions
 *
 */
var initAddress=function(){
    console.log("Address initialzing");

    var start = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Ball-Pink.png"
    var map = null;
    var markerData = null;
    var marker = null;

    $("#new_address_map").hide();
    $("#address").hide();
    $("#new_address_spinner").hide();
    $("#address-info").hide();
    $("#submitNewAddress").hide();

    //Change on Place entering
    $('select[id="new_address_checkout"]').change(function(){
        $("#new_address_checkout_holder").hide();
        var place_id = $("#new_address_checkout option:selected").val();
        var place_name = $("#new_address_checkout option:selected").text();
        console.log("Selected "+place_id);

        $("#address").show();
        $("#address").val(place_name);
        $("#new_address_map").show();
        $("#new_address_spinner").show();
        $("#address-info").show();
        $("#submitNewAddress").show();

        //Get Place lat/lng
        getPlaceDetails(place_id, function(isFetched, data){
            if(isFetched){
                var latAdd = data.lat;
                var lngAdd = data.lng;

                $('#lat').val(latAdd);
                $('#lng').val(lngAdd);


                var mapAddress = new google.maps.Map(document.getElementById('new_address_map'), {
                    zoom: 17,
                    center: new google.maps.LatLng(data.lat, data.lng)
                });

                var markerDataAddress = new google.maps.LatLng(data.lat, data.lng);
                var markerAddress = new google.maps.Marker({
                    position: markerDataAddress,
                    map: mapAddress,
                    icon: start,
                    title: data.name
                });

                mapAddress.addListener('click', function(event) {
                    var data = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                    markerAddress.setPosition(data);

                    var latAdd = event.latLng.lat();
                    var lngAdd = event.latLng.lng();

                    $('#lat').val(latAdd);
                    $('#lng').val(lngAdd);
                });
            }
        });

    });

    //Save on click for location
    $("#submitNewAddress").on("click",function() {
        var address_name = $("#address").val();
        var address_number = $("#address_number").val();
        var number_apartment = $("#number_apartment").val();
        var number_intercom = $("#number_intercom").val();
        var entry = $("#entry").val();
        var floor = $("#floor").val();

        var lat = $("#lat").val();
        var lng = $("#lng").val();

        var doSubmit=true;
        var message="";
        if(address_number.length<1){
            doSubmit=false;
            message+="\nPlease enter address number";
        }

        if(!doSubmit){
            alert(message);
            return false;
        }else{


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
                    lat: lat,
                    lng: lng,
                    apartment: number_apartment,
                    intercom: number_intercom,
                    entry: entry,
                    floor: floor
                },
                success:function(response){
                    if(response.status){
                        //location.replace(response.success_url);
                        window.location.reload();
                    }
                }, error: function (response) {
                    //return callback(false, response.responseJSON.errMsg);
                }
            })
        }

    });
}


/**
 * Fetch lat / lng for specific google place id
 * @param {*} place_id
 * @param {*} callback
 */
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

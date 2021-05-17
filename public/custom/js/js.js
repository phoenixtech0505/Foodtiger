
var js = {
    window_load : function() {
    },
    document_ready : function() {
      js.datepicker();
      js.select();
      js.modal();
    },
    initDriverMap: function(){
      alert("init driver")
    },
    initializeGoogle:function(){
      var input = document.getElementById('txtlocation');
      if(input){
        var autocomplete = new google.maps.places.Autocomplete(input, {  });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();

            IsplaceChange = true;
        });
      }
      
    },
    initStripe: function(STRIPE_KEY,FORM_NAME){
      
      /**
       * STRIPE_KEY -- The stripe key
       * FORM_NAME -- The form name--  ex. 'stripe-payment-form'
       *  
       */


      console.log("Payment initialzing");

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
    var form = document.getElementById(FORM_NAME);
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
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripePaymentId');
                hiddenInput.setAttribute('value', paymentMethod.id);
                form.appendChild(hiddenInput);
        
                // Submit the form
                form.submit();
            }
        }
      })
    },
    modal: function (){
      $('.modal-content').resizable({
        //alsoResize: ".modal-dialog",
        minHeight: 300,
        minWidth: 300
      });
      $('.modal-dialog').draggable();
  
      $('#myModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
          'max-height': '100%'
        });
      });
    },
    select : function(){
      $("select").not(".noselecttwo").each(function( $pos ){
        var $this = $(this);
        if (!$this.hasClass("select2init")){
          $settings = {};
          $this.addClass("select2init");
          
          $('.select2').addClass('form-control');
          $('.select2-selection').css('border','0');
          $('.select2').css('width','300px');
          $('.select2-selection__arrow').css('top','10px');
          $('.select2-selection__rendered').css('color','#8898aa');

          var $ajax = $this.attr("data-feed");
          if (typeof $ajax !== typeof undefined && $ajax !== false){
            $settings.ajax = { url : $ajax, dataType: 'json' }
          }
  
          if (typeof($this.attr("placeholder")) != "undefined"){
            $settings.placeholder = $this.attr("placeholder");
            $settings.id = "-1";
          }
  
          $this.select2($settings);
        }
      });
    },


    datepicker : function(){
       
        $(".daterange").each(function(){
          var $this = $(this);
          var $end = moment();
          var $start = moment().subtract(1, 'month');
          $this.daterangepicker({
            autoUpdateInput: false,
            locale: {cancelLabel: 'Clear'},
            buttonClasses : "datepicker-btn",
            startDate : $start,
            endDate: $end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
               'This year': [moment().startOf('year'), moment()],
               'Last year': [moment().startOf('year').subtract(1, 'years'), moment().subtract(1, 'year')]
            }
          });
          $this.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
          });
          $this.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
          });
        });
      }
}

var $ = jQuery.noConflict();$(window).on("load",function(){js.window_load();});$(document).ready(function() {js.document_ready();});
Number.prototype.pad = function(size) {
  var s = String(this);
  while (s.length < (size || 2)) {s = "0" + s;}
  return s;
}
$.randomID = function(){ return ( Math.random().toString(36).substring(2) ) };
$.tpl = function(template, data){
  return template.replace(/\{([\w\.]*)\}/g, function(str, key) {
    var keys = key.split("."), v = data[keys.shift()];
    for (var i = 0, l = keys.length; i < l; i++) v = v[keys[i]];
    return (typeof v !== "undefined" && v !== null) ? v : "";
  });
};
$.expr[':'].contains = function(a, i, m) { return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; };
@extends('layouts.front', ['class' => ''])

@section('content')
@include('restorants.partials.modals')

    <section class="section-profile-cover section-shaped grayscale-05 d-none d-md-none d-lg-block d-lx-block">
      <!-- Circles background -->
      <img class="bg-image" loading="lazy" src="{{ $restorant->coverm }}" style="width: 100%;">
      <!-- SVG separator -->
      <div class="separator separator-bottom separator-skew">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </section>

    <section class="section pt-lg-0 mb--5 mt--9 d-none d-md-none d-lg-block d-lx-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title white"  <?php if($restorant->description || $openingTime && $closingTime){echo 'style="border-bottom: 1px solid #f2f2f2;"';} ?> >
                        <h1 class="display-3 text-white" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;">{{ $restorant->name }}</h1>
                        <p class="display-4" style="margin-top: 120px">{{ $restorant->description }}</p>
                        <p>@if(!empty($openingTime) && !empty($closingTime))  <i class="ni ni-watch-time"></i> <span>{{ $openingTime }}</span> - <span>{{ $closingTime }}</span> | @endif  @if(!empty($restorant->address))<i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($restorant->address) }}">{{ $restorant->address }}</a>  | @endif @if(!empty($restorant->phone)) <i class="ni ni-mobile-button"></i> <a href="tel:{{$restorant->phone}}">{{ $restorant->phone }} </a> @endif</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    @include('partials.flash')
                </div>
            </div>
        </div>

    </section>
    <section class="section section-lg d-md-block d-lg-none d-lx-none" style="padding-bottom: 0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.flash')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="title">
                        <h1 class="display-3 text" data-toggle="modal" data-target="#modal-restaurant-info" style="cursor: pointer;">{{ $restorant->name }}</h1>
                        <p class="display-4 text">{{ $restorant->description }}</p>
                        <p>@if(!empty($openingTime) && !empty($closingTime))  <i class="ni ni-watch-time"></i> <span>{{ $openingTime }}</span> - <span>{{ $closingTime }}</span> | @endif  @if(!empty($restorant->address))<i class="ni ni-pin-3"></i></i> <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($restorant->address) }}">{{ $restorant->address }}</a>  | @endif @if(!empty($restorant->phone)) <i class="ni ni-mobile-button"></i> <a href="tel:{{$restorant->phone}}">{{ $restorant->phone }} </a> @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section pt-lg-0" id="restaurant-content" style="padding-top: 0px">
        <input type="hidden" id="rid" value="{{ $restorant->id }}"/>
        <div class="container container-restorant">

            
            
            @if(!$restorant->categories->isEmpty())
        <nav class="tabbable sticky" style="top: {{ config('app.isqrsaas') ? 64:88 }}px;">
                <ul class="nav nav-pills bg-white mb-2">
                    <li class="nav-item nav-item-category ">
                        <a class="nav-link  mb-sm-3 mb-md-0 active" data-toggle="tab" role="tab" href="">{{ __('All categories') }}</a>
                    </li>
                    @foreach ( $restorant->categories as $key => $category)
                        @if(!$category->items->isEmpty())
                            <li class="nav-item nav-item-category" id="{{ 'cat_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                                <a class="nav-link mb-sm-3 mb-md-0" data-toggle="tab" role="tab" id="{{ 'nav_'.clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}" href="#{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">{{ $category->name }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                
            </nav>

            
            @endif

            


            @if(!$restorant->categories->isEmpty())
            @foreach ( $restorant->categories as $key => $category)
                @if(!$category->items->isEmpty())
                <div id="{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}" class="{{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                    <h1>{{ $category->name }}</h1><br />
                </div>
                @endif
                <div class="row {{ clean(str_replace(' ', '', strtolower($category->name)).strval($key)) }}">
                    @foreach ($category->items as $item)
                        @if($item->available == 1)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                            <div class="strip">
                                @if(!empty($item->image))
                                <figure>
                                    <a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)"><img src="{{ $item->logom }}" loading="lazy" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                                </figure>
                                @endif
                                <span class="res_title"><b><a onClick="setCurrentItem({{ $item->id }})" href="javascript:void(0)">{{ $item->name }}</a></b></span><br />
                                <span class="res_description">{{ $item->short_description}}</span><br />
                                <span class="res_mimimum">@money($item->price, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            @else
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                        <br/><br/><br/>
                        <div class="text-center" style="opacity: 0.2;">
                            <img src="https://www.jing.fm/clipimg/full/256-2560623_juice-clipart-pizza-box-pizza-box.png" width="200" height="200"></img>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        
        <div onClick="openNav()" class="callOutShoppingButtonBottom icon icon-shape bg-gradient-red text-white rounded-circle shadow mb-4">
            <i class="ni ni-cart"></i>
          </div>

    </section>
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-header bg-transparent pb-2">
                            <h4 class="text-center mt-2 mb-3">{{ __('Call Waiter') }}</h4>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="post" action="{{ route('call.waiter') }}">
                                @csrf
                                @include('partials.fields',$fields)
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary my-4">{{ __('Call Now') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@if ($showLanguagesSelector)
    @section('addiitional_button_1')
        <div class="dropdown web-menu">
            <a href="#" class="btn btn-neutral dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
                <!--<img src="{{ asset('images') }}/icons/flags/{{ strtoupper(config('app.locale'))}}.png" /> --> {{ $currentLanguage }}
            </a>
            <ul class="dropdown-menu" aria-labelledby="">
                @foreach ($restorant->localmenus()->get() as $language)
                    @if ($language->language!=config('app.locale'))
                        <li>
                            <a class="dropdown-item" href="?lang={{ $language->language }}">
                                <!-- <img src="{{ asset('images') }}/icons/flags/{{ strtoupper($language->language)}}.png" /> --> {{$language->languageName}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endsection
    @section('addiitional_button_1_mobile')
        <div class="dropdown mobile_menu">
           
            <a type="button" class="nav-link  dropdown-toggle" data-toggle="dropdown"id="navbarDropdownMenuLink2">
                <span class="btn-inner--icon">
                  <i class="fa fa-globe"></i>
                </span>
                <span class="nav-link-inner--text">{{ $currentLanguage }}</span>
              </a>
            <ul class="dropdown-menu" aria-labelledby="">
                @foreach ($restorant->localmenus()->get() as $language)
                    @if ($language->language!=config('app.locale'))
                        <li>
                            <a class="dropdown-item" href="?lang={{ $language->language }}">
                               <!-- <img src="{{ asset('images') }}/icons/flags/{{ strtoupper($language->language)}}.png" /> ---> {{$language->languageName}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endsection
@endif

@section('js')
<script>
    "use strict";
    var items=[];
    var currentItem=null;
    var currentItemSelectedPrice=null;
    var lastAdded=null;
    var previouslySelected=[];
    var extrasSelected=[];
    var variantID=null;
    var CASHIER_CURRENCY = "<?php echo  config('settings.cashier_currency') ?>";
    var LOCALE="<?php echo  App::getLocale() ?>";
    var debug=true;

    function debugMe(title,message){
        if(debug){
            console.log("#"+title);
            console.log(message);
            console.log("--------");
        }
    }

    /*
    * Price formater
    * @param {Nummber} price
    */
    function formatPrice(price){
        var locale=LOCALE;
        if(CASHIER_CURRENCY.toUpperCase()=="USD"){
            locale=locale+"-US";
        }

        var formatter = new Intl.NumberFormat(locale, {
            style: 'currency',
            currency:  CASHIER_CURRENCY,
        });

        var formated=formatter.format(price);

        return formated;
    }

    /**
     * Load extras for variant
     * @param {Number} variant_id the variant id
     * */
    function loadExtras(variant_id){
        //alert("Load extras for "+variant_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'GET',
                url: '/items/variants/'+variant_id+'/extras',
                success:function(response){
                    if(response.status){
                        response.data.forEach(element => {
                            $('#exrtas-area-inside').append('<div class="custom-control custom-checkbox mb-3"><input onclick="recalculatePrice('+element.item_id+');" class="custom-control-input" id="'+element.id+'" name="extra"  value="'+element.price+'" type="checkbox"><label class="custom-control-label" for="'+element.id+'">'+element.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+'+formatPrice(element.price)+'</label></div>');
                        });
                        $('#exrtas-area').show();

                    }
                }, error: function (response) {
                    //return callback(false, response.responseJSON.errMsg);
                }
            })
        }




    /**
     *
     * Set the selected variant, set price and shows qty area and calls load extras
     * */
    function setSelectedVariant(element){

        //console.log(formated);
        $('#modalPrice').html(formatPrice(element.price));

        //Set current item price
        currentItemSelectedPrice=element.price;

        //Show QTY
        $('.quantity-area').show();

        //Set variantID
        variantID=element.id;

        //Empty the extras, and call it
        $('#exrtas-area-inside').empty();
        loadExtras(variantID);

    }

    function getTheDataForTheFoundVariable(){
        console.log(previouslySelected);
        var comparableObject={};
        previouslySelected.forEach(element => {
            comparableObject[element.option_id]=element.name.trim().toLowerCase().replace(/\s/g , "-");
        });
        comparableObject=JSON.stringify(comparableObject)
        //console.log("Comparable");
        //console.log(comparableObject);
        currentItem['variants'].forEach(element => {
            //console.log("Compare to");
            //console.log(JSON.stringify(JSON.parse(element.options)));
            if(comparableObject==JSON.stringify(JSON.parse(element.options))){
                //console.log("This are the options");
                //console.log(element.options);
                //console.log(element.optionsiconv);
                setSelectedVariant(element);
            }
        });

    }


    function checkIfVariableExists(forOption,optionValue){

        var newElement={"option_id":forOption,"name":optionValue};
        //console.log('NEW ELEMNGT');
        //console.log(newElement);

        var possibleSelection=JSON.parse(JSON.stringify(previouslySelected));
        possibleSelection.push(newElement);
        //console.log(possibleSelection);

        var filteredObjects=[];
        //possibleSelection.forEach(element => {
            currentItem.variants.forEach(theVariant => {
                var theOptions=JSON.parse(theVariant.optionsiconv?theVariant.optionsiconv:theVariant.options);
                var ok=true;
                Object.keys(theOptions).map((key)=>{

                    //console.log(key+" : "+theOptions[key])
                    possibleSelection.forEach(element => {
                        if(key==element.option_id){
                            if(theOptions[key]+""!=element.name.trim().toLowerCase().replace(/\s/g , "-")+""){
                                ok=false;
                            }
                        }
                    });

                })

                if(ok){
                        filteredObjects.push(theVariant);
                        //console.log("ok")
                    }else{
                        //console.log("not ok")
                    }

                //comparableObject[element.option_id]=element.name.trim().toLowerCase().replace(/\s/g , "-");
            });

        //});


        return filteredObjects.length>0;

    }

    function appendOption(name,id){
        lastAdded=id;
        $('#variants-area-inside').append('<div id="variants-area-'+id+'"><br /><label class="form-control-label"><b>'+name+'<b></label><div><div id="variants-area-inside-'+id+'" class="btn-group btn-group-toggle" data-toggle="buttons"> </div></div>');
    }

    function optionChanged(option_id,name){

        var newElement={"option_id":option_id,"name":name};
        debugMe("selected option",JSON.stringify(newElement));

        
        //Append / insert the new selectioin
        var newSelectionState=[];
        var userClickedOnAlreadySelectedOption=false;
        previouslySelected.forEach(element => {

            if(userClickedOnAlreadySelectedOption){
                $( "#variants-area-"+element.option_id ).remove();
            }

            if(element.option_id!=newElement.option_id){
                //If we haven't yet found the item add this in the selection
                if(!userClickedOnAlreadySelectedOption){newSelectionState.push(element);}
            }else{
                userClickedOnAlreadySelectedOption=true;
            }

            
        });



        if(userClickedOnAlreadySelectedOption&&lastAdded!=newElement.option_id){
            //remove also last inserted, and readded it
            $( "#variants-area-"+lastAdded ).remove();
        }

        newSelectionState.push(newElement);
        previouslySelected=newSelectionState;
        debugMe("Selection",JSON.stringify(previouslySelected));
        setVariants();


    }

    function appendOptionValue(name,value,enabled,option_id){
        $('#variants-area-inside-'+option_id).append('<label style="opacity: '+(enabled?1:0.5)+'" class="btn btn-outline-primary"><input  onchange="optionChanged('+option_id+',\''+value+'\')"  '+ (enabled?"":"disabled") +' type="radio" name="option_'+option_id+'" value="option_'+option_id+"_"+name+'" autocomplete="off" />'+name+'</label>')
    }

    function setVariants(){
        //1. Determine previously selected variants
       // var previouslySelected=[];

       //HIDE QTY
       $('.quantity-area').hide();
       $('#exrtas-area-inside').empty();
       $('#exrtas-area').hide();

        //2. Get the new option to show
        var newOptionToShow=null;
        debugMe("previouslySelected length",previouslySelected.length);
        newOptionToShow=currentItem.options[previouslySelected.length];
        debugMe("newOptionToShow",JSON.stringify(newOptionToShow));

        if(newOptionToShow!=undefined){
            //2.1 Add the options in the table
            appendOption(newOptionToShow.name,newOptionToShow.id);


            var values=(newOptionToShow.optionsiconv?newOptionToShow.optionsiconv:newOptionToShow.options).split(",");
            var titles=(newOptionToShow.options).split(",");

            for (let index = 0; index < values.length; index++) {
                const theValue = values[index];
                const theTitle = titles[index];

                if(checkIfVariableExists(newOptionToShow.id,theValue)){
                    //Next variable exists
                    //console.log("Exists: "+theValue);
                    appendOptionValue(theTitle,theValue,true,newOptionToShow.id);
                }else{
                    //Varaiable with the next option value doens't exists
                    //console.log("Does not exists: "+theValue);
                    appendOptionValue(theTitle,theValue,false,newOptionToShow.id);
                }

            }

        }else{
            console.log("No more options");
            getTheDataForTheFoundVariable();
        }




        //3. Add the new option options
        //3.1 If new option is null, show the variant price
    }


    function setCurrentItem(id){


        var item=items[id];
        currentItem=item;
        previouslySelected=[];
        $('#modalTitle').text(item.name);
        $('#modalName').text(item.name);
        $('#modalPrice').html(item.price);
        $('#modalID').text(item.id);

        if(item.image != "/default/restaurant_large.jpg"){
            $("#modalImg").attr("src",item.image);
            $("#modalDialogItem").addClass("modal-lg");
            $("#modalImgPart").show();

            $("#modalItemDetailsPart").removeClass("col-sm-6 col-md-6 col-lg-6 offset-3");
            $("#modalItemDetailsPart").addClass("col-sm col-md col-lg");
        }else{
            $("#modalImgPart").hide();
            $("#modalItemDetailsPart").removeClass("col-sm col-md col-lg");
            $("#modalItemDetailsPart").addClass("col-sm-6 col-md-6 col-lg-6 offset-3");

            $("#modalDialogItem").removeClass("modal-lg");
            $("#modalDialogItem").addClass("col-sm-6 col-md-6 col-lg-6 offset-3");
        }

        $('#modalDescription').html(item.description);


        if(item.has_variants){
            //Vith variants
            //Hide the counter, and extrasts
            $('.quantity-area').hide();

           //Now show the variants options
           $('#variants-area-inside').empty();
           $('#variants-area').show();
           setVariants();
           //$('#modalPrice').html("dynamic");




        }else{
            //Normal
            currentItemSelectedPrice=item.priceNotFormated;
            $('#variants-area').hide();
            $('.quantity-area').show();
        }


        $('#productModal').modal('show');

        extrasSelected=[];

        variantID=null;

        //Now set the extrast
        if(item.extras.length==0||item.has_variants){
            console.log('has no extras');
            $('#exrtas-area-inside').empty();
            $('#exrtas-area').hide();
        }else{
            console.log('has extras');
            $('#exrtas-area-inside').empty();
            item.extras.forEach(element => {
                console.log(element);
                $('#exrtas-area-inside').append('<div class="custom-control custom-checkbox mb-3"><input onclick="recalculatePrice('+id+');" class="custom-control-input" id="'+element.id+'" name="extra"  value="'+element.price+'" type="checkbox"><label class="custom-control-label" for="'+element.id+'">'+element.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+'+element.priceFormated+'</label></div>');
            });
            $('#exrtas-area').show();
        }
    }

    function recalculatePrice(id,value){
        //console.log("Triger price recalculation: "+id);
       // console.log(items[id]);
        var mainPrice=parseFloat(currentItemSelectedPrice);
        extrasSelected=[];

        //Get the selected check boxes
        $.each($("input[name='extra']:checked"), function(){
            mainPrice+=parseFloat(($(this).val()+""));
            extrasSelected.push($(this).attr('id'));
        });
        $('#modalPrice').html(formatPrice(mainPrice));

    }
    <?php

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }

    $items=[];
    $categories = [];
    foreach ($restorant->categories as $key => $category) {

        array_push($categories, clean(str_replace(' ', '', strtolower($category->name)).strval($key)));

        foreach ($category->items as $key => $item) {

            $formatedExtras=$item->extras;

            foreach ($formatedExtras as &$element) {
                $element->priceFormated=@money($element->price, config('settings.cashier_currency'),config('settings.do_convertion'))."";
            }

            //Now add the variants and optins to the item data
            $itemOptions=$item->options;

            $theArray=[
                'name'=>$item->name,
                'id'=>$item->id,
                'priceNotFormated'=>$item->price,
                'price'=>@money($item->price, config('settings.cashier_currency'),config('settings.do_convertion'))."",
                'image'=>$item->logom,
                'extras'=>$formatedExtras,
                'options'=>$item->options,
                'variants'=>$item->variants,
                'has_variants'=>$item->has_variants==1&&$item->options->count()>0,
                'description'=>$item->description
            ];
            echo "items[".$item->id."]=".json_encode($theArray).";";
        }
    }
    ?>
</script>
    <script type="text/javascript">
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

        function initializeMap(lat, lng){
            var map_options = {
                zoom: 13,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: "terrain",
                scaleControl: true
            }

            map_location = new google.maps.Map( document.getElementById("map3"), map_options );
        }

        function initializeMarker(lat, lng){
            var markerData = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                position: markerData,
                map: map_location,
                icon: start
            });
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

        var categories = <?php echo json_encode($categories); ?>;

        window.onload?window.onload():null;

        window.onload = function () {
            //var map, infoWindow, marker, lng, lat;

            getLocation(function(isFetched, currPost){
                if(isFetched){


                    if(currPost.lat != 0 && currPost.lng != 0){
                        //initialize map
                        initializeMap(currPost.lat, currPost.lng)

                        //initialize marker
                        initializeMarker(currPost.lat, currPost.lng)

                        //var isClosed = false;
                    }
                }
            });
        }





        $(".nav-item-category").on('click', function() {
            $.each(categories, function( index, value ) {
                $("."+value).show();

                //$("#nav_"+value).removeClass("active");
            });

            var id = $(this).attr("id");
            var category_id = id.substr(id.indexOf("_")+1, id.length);
            //$("#nav_"+category_id).addClass("active");

            //$("."+category_id).hide();

            $.each(categories, function( index, value ) {
                if(value != category_id){
                    $("."+value).hide();
                }
            });
        });
    </script>
@endsection

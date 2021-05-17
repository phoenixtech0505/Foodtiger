<h6 class="heading-small text-muted mb-4">{{ __('Localisation') }}</h6>

    <!-- Default Language -->
    @if(config('settings.enable_miltilanguage_menus'))
        @include('partials.fields',['fields'=>[
            ['ftype'=>'select','name'=>"Default Language",'id'=>"default_language",'data'=>$available_languages,'required'=>true,'value'=>$default_language],
        ]])
    @endif
    @if(config('app.isqrsaas'))
     <!-- Currency and conversation only in QR -->
     @include('partials.fields',['fields'=>[
        ['ftype'=>'select','name'=>"Currency",'id'=>"currency",'required'=>true,'value'=>$currency,'data'=>config('config.env')[2]['fields'][3]['data']],
        ['name'=>'Money conversion', 'additionalInfo'=>'Some currencies need this field to be unselected. By default it should be selected', 'id'=>'do_covertion', 'value'=>$restorant->do_covertion==1, 'ftype'=>'bool'],
        
        ]])
    @endif



<div class="dropdown">
    <a href="#" class="btn btn-default btn-sm dropdown-toggle " data-toggle="dropdown" id="navbarDropdownMenuLink2">
        <img src="{{ asset('images') }}/icons/flags/{{ strtoupper(config('app.locale'))}}.png" /> {{ $currentLanguage }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
        @foreach ($availableLanguages as $language)
        <li>
            <a class="dropdown-item" href="?lang={{ $language->language }}">
                <img src="{{ asset('images') }}/icons/flags/{{ strtoupper($language->language)}}.png" /> {{$language->languageName}}
            </a>
        </li>
        @endforeach
        <li>
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-new-language">
                <span class="btn-inner--icon"><i class="fa fa-language"></i> {{ __('Add new language') }}</span>
            </a>
        </li>
        @if (config('app.locale')!=$defaultLanguage)
            <li>
                <a href="?make_default_lang={{ config('app.locale') }}" class="dropdown-item" >
                    <span class="btn-inner--icon"><i class="fa fa-check"></i> {{ __('Make')." ".$currentLanguage." ".__("default") }}</span>
                </a>
            </li> 
        @endif
        @if (count($availableLanguages)>1)
            <li>
                <a href="?remove_lang={{ config('app.locale') }}" class="dropdown-item" >
                    <span class="btn-inner--icon"><i class="fa fa-trash"></i> {{ __('Remove')." ".$currentLanguage }}</span>
                </a>
            </li> 
        @endif
        
    </ul>
</div>
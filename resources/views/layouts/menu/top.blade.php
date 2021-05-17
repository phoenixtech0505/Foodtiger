<nav id="navbar-main" class="navbar navbar-light navbar-expand-lg fixed-top">


  <div class="container-fluid">
      <a class="navbar-brand mr-lg-5" href="/">
        <img src="{{ config('global.site_logo') }}">
      </a>
      @if( request()->get('location') )
      <span style="z-index: 10" class="">{{ __('DELIVERING TO')}} :  <b>{{request()->get('location')}}</b></span> <a   data-toggle="modal"  href="#locationset"><span class="ml-sm-2 search description">({{ __('change')}})</span></a>






      @endif
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbar_global">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="#">
                <img src="{{ config('global.site_logo') }}">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <ul class="navbar-nav align-items-lg-center ml-lg-auto">
          
          @if (!config('settings.single_mode')&&config('settings.restaurant_link_register_position')=="navbar")
            <li class="nav-item">
              <a data-mode="popup" target="_blank" class="button nav-link nav-link-icon" href="{{ route('newrestaurant.register') }}">{{ __(config('settings.restaurant_link_register_title')) }}</a>
            </li>
          @endif
          @if (config('app.isft')&&config('settings.driver_link_register_position')=="navbar")
          <li class="nav-item">
              <a data-mode="popup" target="_blank" class="button nav-link nav-link-icon" href="{{ route('driver.register') }}">{{ __(config('settings.driver_link_register_title')) }}</a>
            </li>
            @endif
          <li class="nav-item">
            <a class="nav-link nav-link-icon" href="{{ config('global.facebook') }}" target="_blank" data-toggle="tooltip" title="{{ __('Like us on Facebook') }}">
              <i class="fa fa-facebook-square"></i>
              <span class="nav-link-inner--text d-lg-none">{{ __('Facebook') }}</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-icon" href="{{ config('global.instagram') }}" target="_blank" data-toggle="tooltip" title="{{ __('Follow us on Instagram') }}">
              <i class="fa fa-instagram"></i>
              <span class="nav-link-inner--text d-lg-none">{{ __('Instagram') }}</span>
            </a>
          </li>
          @yield('addiitional_button_1')
          @yield('addiitional_button_2')
          <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
            <li class="nav-item dropdown">
                @auth()
                    @include('layouts.menu.partials.auth')
                @endauth
                @guest()
                    @include('layouts.menu.partials.guest')
                @endguest
            </li>
            <li class="web-menu">
             

              @if(\Request::route()->getName() != "cart.checkout")
                <a  id="desCartLink" onclick="openNav()" class="btn btn-neutral btn-icon btn-cart" style="cursor:pointer;">
                  <span class="btn-inner--icon">
                    <i class="fa fa-shopping-cart"></i>
                  </span>
                  <span class="nav-link-inner--text">{{ __('Cart') }}</span>
              </a>
              @endif

            </li>
            <li class="mobile-menu">
              @yield('addiitional_button_1_mobile')
              @yield('addiitional_button_2_mobile')
              @if(\Request::route()->getName() != "cart.checkout")
                <a  id="mobileCartLink" onclick="openNav()" class="nav-link" style="cursor:pointer;">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="nav-link-inner--text">{{ __('Cart') }}</span>
                </a>
              @endif


            </li>
          </ul>
        </ul>
      </div>
    </div>

  </nav>

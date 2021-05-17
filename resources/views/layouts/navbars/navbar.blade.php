@auth()
    @if(\Request::route()->getName() != "order.success")
        @include('layouts.navbars.navs.auth')
    @endif
@endauth

@guest()
    @if(\Request::route()->getName() != "order.success")
        @include('layouts.navbars.navs.guest')
    @endif
@endguest

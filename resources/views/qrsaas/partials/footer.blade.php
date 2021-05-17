


<footer class="footer section pt-6 pt-md-8 pt-lg-10 pb-3 bg-primary text-white overflow-hidden">
    <div class="pattern pattern-soft top"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                        <a href="#" class="icon icon-lg text-white mr-3 ">
                           <h3>{{  config('app.name') }}</h3>
                        </a>

                <p class="my-4">{{ __('qrlanding.hero_title')}}<br />{{ __('qrlanding.hero_subtitle') }}</p>

            </div>
            <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-lg-0">
                <h6>{{ __('qrlanding.helpful_links')     }}</h6>
                <ul class="links-vertical">
                    @foreach ($pages as $page)
                        <li><a target="_blank" href="/blog/{{ $page->slug }}">{{ $page->title }}</a></li>
                    @endforeach
                    </ul>
            </div>

            <div class="col-6 col-sm-3 col-lg-3 mb-4 mb-lg-0">
                <h6>{{ __('qrlanding.my_account')     }}</h6>
                <ul class="links-vertical">
                    <li><a target="_blank" href="/login">

                        @auth()
                            {{ __('qrlanding.dashboard')}}
                        @endauth
                        @guest()
                            {{ __('qrlanding.login')}}
                        @endguest

                    </a></li>
                    @guest()
                    <li><a target="_blank" href="{{ route('newrestaurant.register') }}">{{ __('qrlanding.register') }}</a></li>
                    @endguest
                </ul>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                    @guest()
                        <h6>{{ __('qrlanding.register')     }}</h6>
                        <form action="{{ route('newrestaurant.register') }}" class="d-flex flex-column mb-5 mb-lg-0">
                            <input class="form-control" type="text" name="name" placeholder="{{ __('qrlanding.hero_input_name')}}" required>
                            <input class="form-control my-3" type="email" name="email" placeholder="{{ __('qrlanding.hero_input_email')}}" required>
                            <input class="form-control my-1" type="text" name="phone" placeholder="{{ __('qrlanding.hero_input_phone')}}" required>
                            <button class="btn btn-primary my-3" type="submit">{{ __('qrlanding.join_now')}}</button>
                        </form>
                    @endguest
            </div>


        </div>

        @include('cookieConsent::index')
        <hr class="my-4 my-lg-5">
        <div class="row">
            <div class="col pb-4 mb-md-0">
                <div class="d-flex text-center justify-content-center align-items-center">
                    <p class="font-weight-normal mb-0">© <a href="{{ config('app.url') }}"
                            target="_blank">{{  config('app.name') }}</a></a>
                        <span class="current-year">2020</span>. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

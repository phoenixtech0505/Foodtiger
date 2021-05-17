<section class="section-header pb-7 pb-lg-11 bg-soft">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-md-6 order-2 order-lg-1">
            <img src="{{ asset('impactfront') }}/img/flayer.png" alt="">
            </div>
            <div class="col-12 col-md-5 order-1 order-lg-2">
            <i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none"></i> <h1 class="display-2 mb-3 ckedit" key="contactles_menu" id="contactles_menu">{{__('qrlanding.contactles_menu')}}</h1>
            <i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none"></i> <p class="lead ckedit" key="hero_title" id="hero_title">{{ __('qrlanding.hero_title')}}</p>
            <i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none"></i> <p class="lead ckedit" key="hero_subtitle" id="hero_subtitle"><strong> {{ __('qrlanding.hero_subtitle') }}</strong></p>
                  <div class="mt-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @guest()
                        <form action="{{ route('newrestaurant.register') }}" class="d-flex flex-column mb-5 mb-lg-0">
                            <input class="form-control" type="text" name="name" placeholder="{{ __('qrlanding.hero_input_name')}}" required>
                            <input class="form-control my-3" type="email" name="email" placeholder="{{ __('qrlanding.hero_input_email')}}" required>
                            <input class="form-control my-1" type="text" name="phone" placeholder="{{ __('qrlanding.hero_input_phone')}}" required>
                            <button class="btn btn-primary my-3" type="submit">{{ __('qrlanding.join_now')}}</button>
                        </form>
                    @endguest
                  </div>
              </div>
        </div>
    </div>
    <div class="pattern bottom"></div>
</section>

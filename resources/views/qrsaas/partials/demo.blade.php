 <!-- DEMO -->
 <section id="demo" class="section section-lg pb-5 bg-soft">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
            <i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none"></i><h2 class="mb-4 ckedit" key="demo_title" id="demo_title">{{ __('qrlanding.demo_title') }}</h2>
            <i class="fas fa-edit mr-2 text-primary ckedit_btn" type="button" style="display: none"></i><p class="lead mb-5 ckedit" key="demo_subtitle" id="demo_subtitle">{{ __('qrlanding.demo_subtitle') . " " }}<span class="font-weight-bolder">{{ __('qrlanding.qr_code') }}</span> {{ __('qrlanding.below') }}!</p>
                <a href="#" class="icon icon-lg text-gray mr-3">
                    <img style="width:300px" src="{{ asset('impactfront') }}/img/qrdemo.jpg" />

                </a>

            </div>
            <div class="col-12 text-center">
                <!-- Button Modal -->
                <a href="{{ route('newrestaurant.register') }}" class="btn btn-secondary animate-up-2"><span class="mr-2"><i class="fas fa-hand-pointer"></i></span>{{ __('qrlanding.demo_button') }}</a>
            </div>
        </div>
    </div>
</section>

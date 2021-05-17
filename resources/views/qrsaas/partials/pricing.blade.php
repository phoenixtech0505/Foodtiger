<section id="pricing" class="section-header bg-primary text-white">
    <div class="container">

        <div class="row justify-content-center mb-6">
            <div class="col-12 col-md-10 text-center">
            <i class="fas fa-edit mr-2 text-white ckedit_btn" type="button" style="display: none"></i><h1 class="display-2 mb-3 ckedit" key="pricing_title" id="pricing_title">{{ __('qrlanding.pricing_title') }}</h1>
            <i class="fas fa-edit mr-2 text-white ckedit_btn" type="button" style="display: none"></i><p class="lead px-5 ckedit" key="pricing_subtitle" id="pricing_subtitle">{{__('qrlanding.pricing_subtitle')}}</p>
            </div>
        </div>
        <div class="row text-gray">
            @foreach ($plans as $plan)
                @include('qrsaas.partials.plan',['plan'=>$plan,'col'=>$col])
            @endforeach
        </div>

    </div>

</section>

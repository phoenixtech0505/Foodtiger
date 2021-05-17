@extends('layouts.app', ['title' => __('Coupon')])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                            @if(isset($coupon))
                                <h3 class="mb-0">{{ __('Edit coupon') }}</h3>
                            @else
                                <h3 class="mb-0">{{ __('New coupon') }}</h3>
                            @endif
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('admin.restaurant.coupons.index') }}" class="btn btn-sm btn-primary">{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="heading-small text-muted mb-4">{{ __('Coupon information') }}</h6>
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="pl-lg-4">
                        @if(isset($coupon))
                            <form method="post" action="{{ route('admin.restaurant.coupons.update', $coupon->id) }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                        @else
                            <form method="post" action="{{ route('admin.restaurant.coupons.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                        @endif
                            @include('coupons.form')
                            <div>
                                @if(isset($coupon))
                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Update')}}</button>
                                @else
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footers.auth')
</div>
@endsection

@section('js')
    <script>
        "use strict";
        /*$('#type option').each(function() {
            if($(this).is(':selected')){
                alert($(this).value)
            }
        })*/

        var coupon = <?php if(isset($coupon)) { echo json_encode($coupon); } else { echo json_encode(null); } ?>;
        if(coupon != null){
            var coupon_type = coupon.type;
            if(coupon_type == 0){
                $('#form-group-price_fixed').show();

                $("#price_fixed").attr("required",true);
                $("#price_percentage").attr("required",false);
            }else{
                $('#form-group-price_percentage').show();

                $("#price_percentage").attr("required",true);
                $("#price_fixed").attr("required",false);
            }
        }

        $('#type').on('change', function() {
            if(this.value == 0){
                $("#price_percentage").attr("required",false);
                $('#form-group-price_percentage').hide();

                $('#form-group-price_fixed').show();
                $("#price_fixed").attr("required",true);

            }else{
                $('#form-group-price_fixed').hide();
                $("#price_fixed").attr("required",false);

                $('#form-group-price_percentage').show();
                $("#price_percentage").attr("required",true);
            }
        });
    </script>
@endsection

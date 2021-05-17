<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Orders') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $last30daysOrders }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-shopping-basket"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Sales Volume') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0"> @money( $last30daysOrdersValue, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            @if(auth()->user()->hasRole('admin'))
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Number of restaurants') }}</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $countItems }} {{ __('restaurants') }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(auth()->user()->hasRole('owner'))
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Number of items') }}</h5>
                                        <span class="h2 font-weight-bold mb-0">{{ $countItems }} {{ __('items') }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Views') }}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $allViews }} {{ __('views') }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <br/>
            @if(auth()->user()->hasRole('admin'))
            @if(config('app.isft'))
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Delivery Fee') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0"> @money($last30daysDeliveryFee, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Static Fee') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0">@money($last30daysStaticFee, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Dynamic Fee') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0">@money($last30daysDynamicFee, config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Total Fee') }} ( 30 {{ __('days') }} )</h5>
                                    <span class="h2 font-weight-bold mb-0">@money(( $last30daysTotalFee != null ? $last30daysTotalFee:0), config('settings.cashier_currency'),config('settings.do_convertion'))</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

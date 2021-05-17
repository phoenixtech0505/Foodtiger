<div class="card card-profile shadow">
    <div class="card-header">
        <h5 class="h3 mb-0">{{ __("Restaurant Location")}}</h5>
    </div>
    <div class="card-body">
        <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">{{ __('Location') }}</a>
                </li>
                @if(config('app.isft'))
                    @if ($restorant->can_deliver == 1)
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{ __('Delivery Area') }}</a>
                        </li>
                    @endif
                @endif
            </ul>
        </div>
        <div class="card shadow">
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                        <div id="map_location" class="form-control form-control-alternative"></div>
                    </div>
                    <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                        <div id="map_area" class="form-control form-control-alternative"></div>
                            <br/>
                            <button type="button" id="clear_area" class="btn btn-danger btn-sm btn-block">{{ __("Clear Delivery Area")}}</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

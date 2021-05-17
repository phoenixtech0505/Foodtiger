<div class="card card-profile bg-secondary shadow">
    <div class="card-header">
        <h5 class="h3 mb-0">{{ __("Working Hours")}}</h5>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('restaurant.workinghours') }}" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="rid" name="rid" value="{{ $restorant->id }}"/>
            <div class="form-group">
                @foreach($days as $key => $value)
                <br/>
                <div class="row">
                    <div class="col-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="days" class="custom-control-input" id="{{ 'day'.$key }}" value={{ $key }}>
                            <label class="custom-control-label" for="{{ 'day'.$key }}">{{ __($value) }}</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                            </div>
                            <input id="{{ $key.'_from' }}" name="{{ $key.'_from' }}" class="flatpickr datetimepicker form-control" type="text" placeholder="{{ __('Time') }}">
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <p class="display-4">-</p>
                    </div>
                    <div class="col-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-time-alarm"></i></span>
                            </div>
                            <input id="{{ $key.'_to' }}" name="{{ $key.'_to' }}" class="flatpickr datetimepicker form-control" type="text" placeholder="{{ __('Time') }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>

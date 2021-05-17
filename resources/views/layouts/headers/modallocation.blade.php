 <!-- Modal -->
 <div class="modal fade" id="locationset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <form>
    <div class="modal-dialog mt-10" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('Change location') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">

                <div class="input-group mb-4">
                    <input class="form-control" name="location" id="txtlocation" value="{{request()->get('location')}}" placeholder="{{ __('Enter your street or address') }}" type="text" required>
                    <div type="button" class="input-group-append button">
                        <span class="input-group-text"><i id="search_location" class="ni ni-pin-3" data-toggle="tooltip" data-placement="top" title="{{ __('Get my current location') }}"></i></span>
                    </div>
                    @if ($errors->has('location'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('location') }}</strong>
                        </span>
                    @endif
                </div>

        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
        </div>

    </div>
    </div>
</form>
</div>

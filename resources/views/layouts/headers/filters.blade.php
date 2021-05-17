<section class="section">
    <div class="container">
        @if(\Request::has('expedition')&&strlen(\Request::input('expedition'))>1)
        <br/><br/><br/>
        <div class="col-12">
            <div class="form-group">
                <div class="row">
                    <label class="form-control-label" >{{ __('Deliverys') }}&nbsp;&nbsp;&nbsp;</label>
                        <label class="custom-toggle">
                            <input type="checkbox" id="expedition_toggle" <?php if(\Request::input('expedition') == "pickup"){echo "checked";}?>>
                            <span class="custom-toggle-slider rounded-circle"></span>
                        </label>
                    <label class="form-control-label" >&nbsp;&nbsp;{{ __('Pickup') }}</label>
                </div>
            </div>
        </div>
        @endif
        <div class="card shadow mt-5 mb-5 col-lg-6 col-sm-12">
            <form id="theQuryForm">
                <div class="card-body">
                    <div class="container search">
                    <div class="row">
                        <div class="col-3 col-lg-1  ">
                            <span class="search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M20.4989485,19.7847749 L19.7848083,20.4989151 L14.464571,15.1786777 C11.9860659,17.2743894 8.29591013,17.0430407 6.09855923,14.6541653 C3.90118417,12.2652635 3.97833814,8.56861232 6.27347523,6.27347523 C8.56861232,3.97833814 12.2652635,3.90118417 14.6541653,6.09855923 C17.04296,8.29583589 17.2743738,11.9858166 15.1782471,14.4650787 L20.4989485,19.7847749 Z M14.415008,13.8189639 C16.164669,11.7497903 15.9715459,8.66902636 13.9771779,6.83455387 C11.9828099,5.00008139 8.89667087,5.06449316 6.98058201,6.98058201 C5.06449316,8.89667087 5.00008139,11.9828099 6.83455387,13.9771779 C8.66902636,15.9715459 11.7497903,16.164669 13.8176685,14.4161071 L14.1437265,14.138492 L14.415008,13.8189639 Z"></path></svg>
                            </span>
                        </div>

                        <div class="col-6 col-lg-8 ">
                            <div class="form-group mt--2 mb--2">
                                <input name="q" type="text" value="{{ request()->get('q') }}" placeholder="{{ __('Search for restaurant, cuisines, and dishes') }}" class="success form-control form-control-alternative is-valid">
                                <input name="location" value="{{ request()->get('location') }}" type="hidden" />
                            </div>
                        </div>
                        <input type="hidden" value="" name="expedition" id="expedition"/>
                        <div class="col-3 col-lg-3 ">
                            <a  onclick="document.getElementById('theQuryForm').submit();" type="submit" href="javascript:;"><span class="search">
                                {{ __('Search')}}
                            </span></a>
                        </div>

                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>


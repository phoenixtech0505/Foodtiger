@extends('layouts.front', ['title' => __('Restaurants')])

@section('content')
<div class="section section-hero section-shaped">
      
    <div class="section features-1">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <form>
                    <div class="form-group">
                        <div class="input-group mb-4 border border-danger">
                        <input class="form-control" name="location" id="txtlocation" value="{{ request()->get('location') }}" type="text">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="ni ni-pin-3"></i></span>
                        </div>
                    </div>
                </form>
            </div>
          </div>
          <div class="col-md-9">
            <h6 class="info-title text-uppercase text-warning">Recommended for you</h6>
            <div class="row">
                @forelse ($restorants as $restorant)
                    <?php $link=route('vendor',['alias'=>$restorant->alias]); ?>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                        <div class="strip">
                            <figure>
                            <a href="{{ $link }}"><img src="{{ $restorant->logom }}" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                            </figure>
                            <span class="res_title"><b><a href="{{ $link }}">{{ $restorant->name}}</a></b></span><br />
                            <span class="res_description">{{ $restorant->description}}</span><br />
                            <span class="res_mimimum">{{ __('Minimum order') }}: @money($restorant->minimum, config('settings.cashier_currency'),config('settings.do_convertion'))</span>

                        </div>
                    </div>
                    @empty
                    <div class="col-md-12">
                    <p class="text-muted mb-0">{{ __('Hmmm... Nothing found!')}}</p>
                    </div>

                    @endforelse
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@extends('layouts.app', ['title' => __('settings.system_status')])

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
                            <h3 class="mb-0">{{ __('settings.system_status') }}</h3>
                        </div>
                        <div class="col-4 text-right">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($testResutls as $item)
                        @if ($item[2])
                            <div class="card" style="width: 40rem;">
                                <div class="card-body">
                                <h5 class="card-text">{{ __($item[0]) }} - {{ __($item[1]) }}</h5>
                                </div>
                            </div>
                            <br />
                        @else
                            <div class="card" style="width: 40rem;">
                                <div class="card-body">
                                <h5 class="card-text">{{ __($item[0]) }}</h5>
                                <p class="card-text">{{ __($item[1]) }}</p>
                                <a href="{{$item[3]}}" class="btn btn-primary">{{ __('settings.how_to_fix_this') }}</a>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    
                      

                    <div class="progress-wrapper">
                        
                        <div class="progress-info">
                            <div class="progress-label warning">
                            <span>{{ __('settings.setup_progress')}}</span>
                              </div>
                          <div class="progress-percentage">
                          <span>{{ $progress }}%</span>
                          </div>
                        </div>
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress }}%;"></div>
                        </div>
                      </div>

                      

                </div>
            </div>
        </div>
    </div>
</div>
@endsection()

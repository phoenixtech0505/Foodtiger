@section('js')
    <script src="{{ asset('custom') }}/js/liveorders.js"></script>
@endsection
 
@section('admin_title')
    {{__('Live orders')}}
@endsection

@extends('layouts.app', ['title' => __('Orders')])

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid d-flex align-items-center">
        <div class="row">
            <div class="col-md-12 {{ $class ?? '' }}">
                <h1 style="display:inline"  class="display-2 text-white">{{ __("Live orders") }}</h1>



            </div>
        </div>
    </div>

    </div>

    <div class="container-fluid mt--7">
    <div id="liveorders" class="row">
        
        
        <div class="col-xl-4">
          <!-- Members list group card -->
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <!-- Title -->
              <h5 class="h3 mb-0">{{ __('New Orders')}}</h5>
            </div>
            <!-- Card body -->
            <div class="card-body">
              <!-- List group -->
              <ul class="list-group list-group-flush list my--3">

              
                <li v-for="item in neworders" class="list-group-item px-0">
                    
                    @include('orders.partials.liveitem')
                </li>

              </ul>
            </div>
          </div>
        </div>

        <div class="col-xl-4">
            <!-- Members list group card -->
            <div class="card">
              <!-- Card header -->
              <div class="card-header">
                <!-- Title -->
                <h5 class="h3 mb-0">{{ __('Accepted')}}</h5>
              </div>
              <!-- Card body -->
              <div class="card-body">
                <!-- List group -->
                <ul class="list-group list-group-flush list my--3">
  
                
                  <li v-for="item in accepted" class="list-group-item px-0">
                    @include('orders.partials.liveitem')
                  </li>
  
                </ul>
              </div>
            </div>
        </div>



        <div class="col-xl-4">
            <!-- Members list group card -->
            <div class="card">
              <!-- Card header -->
              <div class="card-header">
                <!-- Title -->
                <h5 class="h3 mb-0">{{ __('Done')}}</h5>
              </div>
              <!-- Card body -->
              <div class="card-body">
                <!-- List group -->
                <ul class="list-group list-group-flush list my--3">
  
                
                  <li v-for="item in done" class="list-group-item px-0">
                      @include('orders.partials.liveitem')
                  </li>
  
                </ul>
              </div>
            </div>
        </div>

          
      </div>
      


        @include('layouts.footers.auth')
    </div>
    <div class="modal fade modal-xl" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-l modal-dialog-centered" style="max-width:1140px" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-default">#123456  - February 8, 10:30 PM  </h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
            <div class="row">
            <div class="col-md-7">
            <h4>Restorant Name<h4>
                <p>Address, Owner name, Owner phone, Owner email</p>
                <h4>Client Name<h4>
                <p>Address, Client phone, Client email</p>
                <h4>Order</h4>
                <p>
                    <ol>
                        <li>4x Pizza Family         20.00$</li>
                        <li>2x Bake Rools (extra: mashrooms, cheese)         20.00$</li>
                        <li>4x Trout Fish         20.00$</li>
                        <li>1x Macedonia Salas (extra: mashrooms, cheese)         20.00$</li>
                    </ol>
                </p>
                <h4>Delevery: 2,00 $<h4>
                <h4>Total<h4>
                <p>95.00 $</p>
            </div>
            <div class="col-md-5">
                    <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                    <!-- Title -->
                    <h5 class="h3 mb-0">{{ __("Status History")}}</h5>
                    </div>
                    <!-- Card body -->
                    <div class="card-body">
                    <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                        <div class="timeline-block">
                        <span class="timeline-step badge-success">
                            <i class="ni ni-bell-55"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between pt-1">
                            <div>
                                <span class="text-muted text-sm font-weight-bold">Order received</span>
                            </div>
                            <div class="text-right">
                                <small class="text-muted"><i class="fas fa-clock mr-1"></i>2 hrs ago</small>
                            </div>
                            </div>
                            <h6 class="text-sm mt-1 mb-0">Client CLIENT_NAME makes the order</h6>
                        </div>
                        </div>
                        <div class="timeline-block">
                        <span class="timeline-step badge-danger">
                            <i class="ni ni-html5"></i>
                        </span>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between pt-1">
                            <div>
                                <span class="text-muted text-sm font-weight-bold">Order Accepted by Admin</span>
                            </div>
                            <div class="text-right">
                                <small class="text-muted"><i class="fas fa-clock mr-1"></i>3 hrs ago</small>
                            </div>
                            </div>
                            <h6 class="text-sm mt-1 mb-0">Admin review the order and accepts it.</h6>
                        </div>
                        </div>
                        <div class="timeline-block">
                            <span class="timeline-step badge-info">
                                <i class="ni ni-like-2"></i>
                            </span>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between pt-1">
                                <div>
                                    <span class="text-muted text-sm font-weight-bold">Order Accepted by Restorant</span>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted"><i class="fas fa-clock mr-1"></i>5 hrs ago</small>
                                </div>
                                </div>
                                <h6 class="text-sm mt-1 mb-0">Restorant review the order and accepted</h6>
                            </div>
                        </div>
                        <div class="timeline-block">
                            <span class="timeline-step badge-info">
                                <i class="ni ni-like-2"></i>
                            </span>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between pt-1">
                                <div>
                                    <span class="text-muted text-sm font-weight-bold">Addmin assigns Driver</span>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted"><i class="fas fa-clock mr-1"></i>5 hrs ago</small>
                                </div>
                                </div>
                                <h6 class="text-sm mt-1 mb-0">Order is assigned to driver DRIVER NAME</h6>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            </div>
            </div>




            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Accept</button>
                <button type="button" class="btn btn-primary">Assign to driver</button>
                <button type="button" class="btn btn-primary">Prepared</button>
                <button type="button" class="btn btn-danger">Reject</button>
                <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button>
            </div>

        </div>
        </div>
        </div>

@endsection

@extends('layouts.app', ['title' => __('Restaurants')])
@section('admin_title')
    {{__('Restaurants')}}
@endsection
@section('content')
    @include('restorants.partials.modals')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Restaurants') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.restaurants.create') }}" class="btn btn-sm btn-primary">{{ __('Add Restaurant') }}</a>
                                @if(auth()->user()->hasRole('admin') && config('settings.enable_import_csv'))
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-import-restaurants">{{ __('Import from CSV') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Logo') }}</th>
                                    <th scope="col">{{ __('Owner') }}</th>
                                    <th scope="col">{{ __('Owner email') }}</th>
                                    <th scope="col">{{ __('Creation Date') }}</th>
                                    <th scope="col">{{ __('Active') }}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($restorants as $restorant)
                                    <tr>
                                        <td><a href="{{ route('admin.restaurants.edit', $restorant) }}">{{ $restorant->name }}</a></td>
                                        <td><img class="rounded" src={{ $restorant->icon }} width="50px" height="50px"></img></td>
                                        <td>{{  $restorant->user?$restorant->user->name:__('Deleted') }}</td>
                                        <td>
                                            <a href="mailto: {{ $restorant->user?$restorant->user->email:""  }}">{{  $restorant->user?$restorant->user->email:__('Deleted')  }}</a>
                                        </td>
                                        <td>{{ $restorant->created_at->format(config('settings.datetime_display_format')) }}</td>
                                        <td>
                                           @if($restorant->active == 1)
                                                <span class="badge badge-success">{{ __('Active') }}</span>
                                           @else
                                                <span class="badge badge-warning">{{ __('Not active') }}</span>
                                           @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                        <a class="dropdown-item" href="{{ route('admin.restaurants.edit', $restorant) }}">{{ __('Edit') }}</a>
                                                        <a class="dropdown-item" href="{{ route('admin.restaurants.loginas', $restorant) }}">{{ __('Login as') }}</a>
                                                        <form action="{{ route('admin.restaurants.destroy', $restorant) }}" method="post">
                                                            @csrf
                                                            @method('delete')
                                                            @if($restorant->active == 0)
                                                                <a class="dropdown-item" href="{{ route('restaurant.activate', $restorant) }}">{{ __('Activate') }}</a>
                                                            @else
                                                            <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to deactivate this restaurant?") }}') ? this.parentElement.submit() : ''">
                                                                {{ __('Deactivate') }}
                                                            </button>
                                                            @endif
                                                            
                                                            
                                                        </form>
                                                    <a class="dropdown-item warning red" onclick="return confirm('Are you sure you want to delete this Restaurant from Database? This will aslo delete all data related to it. This is irreversible step.')"  href="{{ route('admin.restaurant.remove',$restorant)}}">{{ __('Delete') }}</a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $restorants->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

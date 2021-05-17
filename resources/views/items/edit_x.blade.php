@extends('layouts.app', ['title' => __('Item Management')])

@section('content')
    @include('items.partials.header', ['title' => __('Edit Item')])
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ __('Item Management') }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                @if(auth()->user()->hasRole('owner'))
                                    <a href="{{ route('items.index') }}" class="btn btn-sm btn-primary">{{ __('Back to items') }}</a>
                                @elseif(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('items.admin', $restorant) }}" class="btn btn-sm btn-primary">{{ __('Back to items') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Item information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('items.update', $item) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group{{ $errors->has('item_name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_name">{{ __('Item Name') }}</label>
                                            <input type="text" name="item_name" id="item_name" class="form-control form-control-alternative{{ $errors->has('item_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('item_name', $item->name) }}" required autofocus>
                                            @if ($errors->has('item_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('item_description') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_description">{{ __('Item Description') }}</label>
                                            <textarea id="item_description" name="item_description" class="form-control form-control-alternative{{ $errors->has('item_description') ? ' is-invalid' : '' }}" placeholder="{{ __('Item Description here ... ') }}" value="{{ old('item_description', $item->description) }}" required autofocus rows="3">{{ old('item_description', $item->description) }}</textarea>
                                            @if ($errors->has('item_description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('item_price') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_price">{{ __('Item Price') }}</label>
                                            <input type="number" step="any" name="item_price" id="item_price" class="form-control form-control-alternative{{ $errors->has('item_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('item_price', $item->price) }}" required autofocus>
                                            @if ($errors->has('item_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <?php $image=['name'=>'item_image','label'=>__('Item Image'),'value'=> $item->logom,'style'=>'width: 290px; height:200']; ?>
                                        @include('partials.images',$image)
                                        <div class="form-group">
                                            <label class="form-control-label" for="item_price">{{ __('Item available') }}</label>
                                            <label class="custom-toggle" style="float: right">
                                                <input type="checkbox" id="itemAvailable" class="itemAvailable" itemid="{{ $item->id }}" <?php if($item->available == 1){echo "checked";}?>>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="text-center">
                                   <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </form>
                            <div class="text-center">
                                <form action="{{ route('items.destroy', $item) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-danger mt-4" onclick="confirm('{{ __("Are you sure you want to delete this item?") }}') ? this.parentElement.submit() : ''">{{ __('Delete') }}</button>
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

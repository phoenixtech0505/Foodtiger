<div class="row">
    <div class="col-md-3">
        @if(isset($banner))
            @include('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter banner name",'required'=>true, 'value'=>$banner->name])
        @else
            @include('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter banner name",'required'=>true])
        @endif
    </div>
    <div class="col-md-3">
        @if(isset($banner))
            @include('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Vendor', 'Blog'],'required'=>true, 'value'=>$banner->type])
        @else
            @include('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Vendor', 'Blog'],'required'=>true])
        @endif
    </div>
    <div class="col-md-3">
        @if(isset($banner) && $banner->type == 0)
            @include('partials.select', ['class'=>"col-12",'name'=>"Vendor",'id'=>"vendor_id",'placeholder'=>"Select restaurant",'data'=>$restaurants,'required'=>false, 'value'=>$banner->vendor_id ])
            @include('partials.select', ['class'=>"col-12",'name'=>"Page",'id'=>"page_id",'placeholder'=>"Select page",'data'=>$pages,'required'=>false, 'value'=>$banner->page_id])
        @elseif(isset($banner) && $banner->type == 1)
        @include('partials.select', ['class'=>"col-12",'name'=>"Vendor",'id'=>"vendor_id",'placeholder'=>"Select restaurant",'data'=>$restaurants,'required'=>false, 'value'=>$banner->vendor_id ])
            @include('partials.select', ['class'=>"col-12",'name'=>"Page",'id'=>"page_id",'placeholder'=>"Select page",'data'=>$pages,'required'=>false, 'value'=>$banner->page_id])
        @else
            @include('partials.select', ['class'=>"col-12",'name'=>"Vendor",'id'=>"vendor_id",'placeholder'=>"Select restaurant",'data'=>$restaurants,'required'=>false])
            @include('partials.select', ['class'=>"col-12",'name'=>"Page",'id'=>"page_id",'placeholder'=>"Select page",'data'=>$pages,'required'=>false])
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="input-daterange datepicker row align-items-center" style="margin-left: 15px;">
           <div class="form-group">
                <label class="form-control-label">{{ __('Active from') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    @if(isset($banner))
                        <input name="active_from" class="form-control" placeholder="{{ __('Active from') }}" value="{{ old('active_from', $banner->active_from) }}" type="text">
                    @else
                        <input name="active_from" class="form-control" placeholder="{{ __('Active from') }}" type="text">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-daterange datepicker row align-items-center" style="margin-left: 15px;">
           <div class="form-group">
                <label class="form-control-label">{{ __('Active to') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    @if(isset($banner))
                        <input name="active_to" class="form-control" placeholder="{{ __('Active to') }}" value="{{ old('active_to', $banner->active_to) }}" type="text">
                    @else
                        <input name="active_to" class="form-control" placeholder="{{ __('Active to') }}" type="text">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @if(isset($banner))
            @include('partials.images',['image'=>['name'=>'banner_image','label'=>__('Image'),'value'=>$banner->imgm,'style'=>'width: 200px; height: 100px;']])
        @else
            @include('partials.images',['image'=>['name'=>'banner_image','label'=>__('Image'),'value'=>'https://www.fastcat.com.ph/wp-content/uploads/2016/04/dummy-post-square-1-768x768.jpg','style'=>'width: 200px; height: 200px;']])
        @endif
    </div>
</div>

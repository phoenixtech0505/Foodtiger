<div class="row">
    <div class="col-md-3">
        @if(isset($coupon))
            @include('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter code name",'required'=>true, 'value'=>$coupon->name])
        @else
            @include('partials.input',['class'=>"col-12", 'ftype'=>'input','name'=>"Name",'id'=>"name",'placeholder'=>"Enter code name",'required'=>true])
        @endif
    </div>
    <div class="col-md-3">
        @if(isset($coupon))
            @include('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Fixed', 'Percentage'],'required'=>true, 'value'=>$coupon->type])
        @else
            @include('partials.select', ['class'=>"col-12",'name'=>"Type",'id'=>"type",'placeholder'=>"Select type",'data'=>['Fixed', 'Percentage'],'required'=>true])
        @endif
    </div>
    <div class="col-md-3">
        @if(isset($coupon) && $coupon->type == 0)
            @include('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency'), 'value'=>$coupon->price])
            @include('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value', 'value'=>$coupon->price])
        @elseif(isset($coupon) && $coupon->type == 1)
        @include('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency'), 'value'=>$coupon->price])
            @include('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value','value'=>$coupon->price])
        @else
            @include('partials.input',['class'=>"col-12", 'type'=>'number', 'name'=>"Price",'id'=>"price_fixed",'placeholder'=>"Enter price here",'required'=>false, 'additionalInfo'=>'Price in '.config('settings.cashier_currency')])
            @include('partials.input',['class'=>"col-12", 'type'=>'number', 'min'=>'1', 'max'=>'100', 'name'=>"Price",'id'=>"price_percentage",'placeholder'=>"Enter percentage here",'required'=>false, 'additionalInfo'=>'Percentage value'])
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
                    @if(isset($coupon))
                        <input name="active_from" class="form-control" placeholder="{{ __('Active from') }}" value="{{ old('active_from', $coupon->active_from) }}" type="text">
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
                    @if(isset($coupon))
                        <input name="active_to" class="form-control" placeholder="{{ __('Active to') }}" value="{{ old('active_to', $coupon->active_to) }}" type="text">
                    @else
                        <input name="active_to" class="form-control" placeholder="{{ __('Active to') }}" type="text">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @if(isset($coupon))
            @include('partials.input',['class'=>"col-12", 'type'=>'number','name'=>"Limit number",'id'=>"limit_to_num_uses",'placeholder'=>"Limit number to use",'required'=>true, 'value'=>$coupon->limit_to_num_uses, 'step'=>1])
        @else
            @include('partials.input',['class'=>"col-12", 'type'=>'number','name'=>"Limit number",'id'=>"limit_to_num_uses",'placeholder'=>"Limit number to use",'required'=>true, 'step'=>1])
        @endif
    </div>
</div>

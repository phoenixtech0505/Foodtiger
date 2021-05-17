<div class="card card-profile bg-secondary shadow">
    <div class="card-header">
        <h5 class="h3 mb-0">{{ __("Subscription plan")}}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('update.plan')}}" >
            @csrf
            <input type="hidden" name="user_id" value="{{ $restorant->user->id }}">
            <input type="hidden" name="restaurant_id" value="{{ $restorant->id }}">
            @include('partials.fields',['fields'=>[
                ['class'=>'col-12', 'ftype'=>'select','name'=>"Current plan",'id'=>"plan_id",'data'=>$plans,'required'=>true,'value'=>$restorant->user->mplanid()],
            ]])
            <div class="text-center">
                <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>

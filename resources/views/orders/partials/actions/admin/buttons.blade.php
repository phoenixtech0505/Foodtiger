<script>
    function setSelectedOrderId(id){
        $("#form-assing-driver").attr("action", "/updatestatus/assigned_to_driver/"+id);
    }
</script>
      
<!-- Accept -->
@if($lastStatusAlisas == "just_created")
    <a href="{{ url('updatestatus/accepted_by_admin/'.$order->id) }}" class="btn btn-primary">{{ __('Accept') }}</a>
@endif

<!-- Reject -->
@if($lastStatusAlisas == "just_created")
    <a href="{{ url('updatestatus/rejected_by_admin/'.$order->id) }}" class="btn btn-danger">{{ __('Reject') }}</a>
@endif

<!-- Assign to driver -->
@if($order->delivery_method.""!="2"&&$order->driver==null)
    @if($lastStatusAlisas == "accepted_by_restaurant"|$lastStatusAlisas == "prepared"|$lastStatusAlisas == "rejected_by_driver")
        <button type="button" class="btn btn-primary" onClick=(setSelectedOrderId({{ $order->id }}))  data-toggle="modal" data-target="#modal-asign-driver">{{ __('Assign to driver') }}</button>
    @endif
@endif
   
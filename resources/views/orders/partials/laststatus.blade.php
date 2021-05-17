<?php
$badgeTypes=['badge-primary','badge-primary','badge-success','badge-success','badge-default','badge-warning','badge-success','badge-info','badge-danger','badge-success','badge-success','badge-success','badge-success'];
?>
@if($order->status->count()>0)
    <span class="badge {{ $badgeTypes[$order->status->pluck('id')->last()] }} badge-pill">{{ __($order->status->pluck('name')->last()) }}</span>
@endif  
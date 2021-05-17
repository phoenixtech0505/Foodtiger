<thead class="thead-light">
    <tr>
        <th scope="col">{{ __('ID') }}</th>
        @hasrole('admin|driver')
            <th scope="col">{{ __('Restaurant') }}</th>
        @endif
        <th class="table-web" scope="col">{{ __('Created') }}</th>
        <th class="table-web" scope="col">{{ __('Method') }}</th>

        <th class="table-web" scope="col">{{ __('Platform fee') }}</th>
        <th class="table-web" scope="col">{{ __('Processor fee') }}</th>
        <th class="table-web" scope="col">{{ __('Delivery') }}</th>
        <th class="table-web" scope="col">{{ __('Net Price + VAT') }}</th>
        <th class="table-web" scope="col">{{ __('VAT') }}</th>
        <th class="table-web" scope="col">{{ __('Net Price') }}</th>
        
        
        <th class="table-web" scope="col">{{ __('Total Price') }}</th>
        
    </tr>
</thead>
<tbody>
@foreach($orders as $order)
<tr>
    <td>
        
        <a class="btn badge badge-success badge-pill" href="{{ route('orders.show',$order->id )}}">#{{ $order->id }}</a>
    </td>
    @hasrole('admin|driver')
    <th scope="row">
        <div class="media align-items-center">
            <a class="avatar-custom mr-3">
                <img class="rounded" alt="..." src={{ $order->restorant->icon }}>
            </a>
            <div class="media-body">
                <span class="mb-0 text-sm">{{ $order->restorant->name }}</span>
            </div>
        </div>
    </th>
    @endif

    <td class="table-web">
        {{ $order->created_at->format(config('settings.datetime_display_format')) }}
    </td>
    <td class="table-web">
        @if ($order->delivery_method==1)
            <span class="badge badge-primary badge-pill">{{ __('Delivery') }} | {{ __($order->payment_method) }} </span>
        @else
            <span class="badge badge-success badge-pill">{{ __('Pickup') }} | {{ __($order->payment_method) }}</span>
        @endif

    </td>
    
    <td class="table-web">
        @money( $order->fee_value+$order->static_fee, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    <td class="table-web">
        @money( $order->payment_processor_fee, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    <td class="table-web">
        @money( $order->delivery_price, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    <td class="table-web">
        @money( $order->order_price-($order->fee_value+$order->static_fee), config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    <td class="table-web">
        @money( $order->vatvalue, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    <td class="table-web">
        @money( $order->order_price-($order->fee_value+$order->static_fee)-$order->vatvalue, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>

    
   
    <td class="table-web">
        @money( $order->order_price+$order->delivery_price, config('settings.cashier_currency'),config('settings.do_convertion'))
    </td>
    
    
</tr>
   

@endforeach
</tbody>
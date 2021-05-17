<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromArray, WithHeadings
{
    protected $orders;

    public function headings(): array
    {
        return [
            'order_id',
            'restaurant_name',
            'restaurant_id',
            'created',
            'last_status',
            'client_name',
            'client_id',
            'table_name',
            'table_id',
            'area_name',
            'area_id',
            'address',
            'address_id',
            'driver_name',
            'driver_id',
            'order_value',
            'order_delivery',
            'order_total',
            'payment_method',
            'srtipe_payment_id',
            'order_fee',
            'restaurant_fee',
            'restaurant_static_fee',
            'vat',
        ];
    }

    public function __construct(array $orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        return $this->orders;
    }
}

<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancesExport implements FromArray, WithHeadings
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
            'address',
            'address_id',
            'driver_name',
            'driver_id',
            'payment_method',
            'srtipe_payment_id',
            'restaurant_fee_percent',
            'order_fee',
            'restaurant_static_fee',
            'platform_fee',
            'processor_fee',
            'delivery',
            'net_price_with_vat',
            'vat',
            'net_price',
            'order_total',
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

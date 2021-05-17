<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasDelivery;

class DeliveryPayPalOrder extends WebServiceOrderRepository
{
    use HasDelivery;
    use HasPayPal;
}
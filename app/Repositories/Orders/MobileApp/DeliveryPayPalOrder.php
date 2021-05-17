<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasDelivery;

class DeliveryPayPalOrder extends MobileAppOrderRepository
{
    use HasDelivery;
    use HasPayPal;
}
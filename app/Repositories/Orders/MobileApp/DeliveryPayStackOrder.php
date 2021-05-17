<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasDelivery;

class DeliveryPayStackOrder extends MobileAppOrderRepository
{
    use HasDelivery;
    use HasPayStack;
}
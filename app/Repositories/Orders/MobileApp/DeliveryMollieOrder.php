<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasDelivery;

class DeliveryMollieOrder extends MobileAppOrderRepository
{
    use HasDelivery;
    use HasMollie;
}
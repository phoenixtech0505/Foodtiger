<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasDelivery;

class DeliveryCODOrder extends MobileAppOrderRepository
{
    use HasDelivery;
    use HasCOD;
}
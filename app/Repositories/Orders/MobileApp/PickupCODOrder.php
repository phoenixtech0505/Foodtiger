<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasPickup;

class PickupCODOrder extends MobileAppOrderRepository
{
    use HasPickup;
    use HasCOD;
}
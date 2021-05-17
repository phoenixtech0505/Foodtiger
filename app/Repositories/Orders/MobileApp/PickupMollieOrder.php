<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasPickup;

class PickupMollieOrder extends MobileAppOrderRepository
{
    use HasPickup;
    use HasMollie;
}
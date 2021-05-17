<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasPickup;

class PickupPayStackOrder extends MobileAppOrderRepository
{
    use HasPayStack;
    use HasMollie;
}
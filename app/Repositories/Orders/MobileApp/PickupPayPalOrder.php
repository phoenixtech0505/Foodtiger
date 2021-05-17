<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasPickup;

class PickupPayPalOrder extends MobileAppOrderRepository
{
    use HasPickup;
    use HasPayPal;
}
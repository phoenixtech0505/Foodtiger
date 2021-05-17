<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasPickup;

class PickupPayPalOrder extends LocalOrderRepository
{
    use HasPickup;
    use HasPayPal;
}
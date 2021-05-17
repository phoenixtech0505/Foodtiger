<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasPickup;

class PickupPayStackOrder extends LocalOrderRepository
{
    use HasPayStack;
    use HasMollie;
}
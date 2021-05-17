<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasPickup;

class PickupMollieOrder extends LocalOrderRepository
{
    use HasPickup;
    use HasMollie;
}
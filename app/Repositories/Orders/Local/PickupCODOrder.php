<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasPickup;

class PickupCODOrder extends LocalOrderRepository
{
    use HasPickup;
    use HasCOD;
}
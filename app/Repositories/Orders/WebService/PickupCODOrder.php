<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasPickup;

class PickupCODOrder extends WebServiceOrderRepository
{
    use HasPickup;
    use HasCOD;
}
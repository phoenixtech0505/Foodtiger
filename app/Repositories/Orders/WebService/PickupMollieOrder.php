<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasPickup;

class PickupMollieOrder extends WebServiceOrderRepository
{
    use HasPickup;
    use HasMollie;
}
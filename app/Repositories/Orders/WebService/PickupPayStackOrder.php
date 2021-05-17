<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasPickup;

class PickupPayStackOrder extends WebServiceOrderRepository
{
    use HasPayStack;
    use HasMollie;
}
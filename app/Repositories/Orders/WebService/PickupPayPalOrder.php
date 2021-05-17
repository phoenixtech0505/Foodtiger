<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasPickup;

class PickupPayPalOrder extends WebServiceOrderRepository
{
    use HasPickup;
    use HasPayPal;
}
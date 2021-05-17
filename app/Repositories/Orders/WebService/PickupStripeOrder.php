<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasPickup;

class PickupStripeOrder extends WebServiceOrderRepository
{
    use HasPickup;
    use HasStripe;
}
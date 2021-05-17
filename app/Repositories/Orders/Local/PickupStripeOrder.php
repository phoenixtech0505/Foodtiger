<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasPickup;

class PickupStripeOrder extends LocalOrderRepository
{
    use HasPickup;
    use HasStripe;
}
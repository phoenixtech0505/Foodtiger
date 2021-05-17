<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasPickup;

class PickupStripeOrder extends MobileAppOrderRepository
{
    use HasPickup;
    use HasStripe;
}
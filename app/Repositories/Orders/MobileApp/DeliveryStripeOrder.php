<?php

namespace App\Repositories\Orders\MobileApp;
use App\Repositories\Orders\MobileAppOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasDelivery;

class DeliveryStripeOrder extends MobileAppOrderRepository
{
    use HasDelivery;
    use HasStripe;
}
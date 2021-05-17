<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasDelivery;

class DeliveryStripeOrder extends WebServiceOrderRepository
{
    use HasDelivery;
    use HasStripe;
}
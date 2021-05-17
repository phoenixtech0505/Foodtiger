<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasDelivery;

class DeliveryPayStackOrder extends WebServiceOrderRepository
{
    use HasDelivery;
    use HasPayStack;
}
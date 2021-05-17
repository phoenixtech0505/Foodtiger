<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasDelivery;

class DeliveryMollieOrder extends WebServiceOrderRepository
{
    use HasDelivery;
    use HasMollie;
}
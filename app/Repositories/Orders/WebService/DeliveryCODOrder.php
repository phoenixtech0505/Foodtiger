<?php

namespace App\Repositories\Orders\WebService;
use App\Repositories\Orders\WebServiceOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasDelivery;

class DeliveryCODOrder extends WebServiceOrderRepository
{
    use HasDelivery;
    use HasCOD;
}
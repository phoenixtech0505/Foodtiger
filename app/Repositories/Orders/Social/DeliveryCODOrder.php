<?php

namespace App\Repositories\Orders\Social;
use App\Repositories\Orders\SocialOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasDelivery;

class DeliveryCODOrder extends SocialOrderRepository
{
    use HasDelivery;
    use HasCOD;
}
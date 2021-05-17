<?php

namespace App\Repositories\Orders\Social;
use App\Repositories\Orders\SocialOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasPickup;

class PickupCODOrder extends SocialOrderRepository
{
    use HasPickup;
    use HasCOD;
}
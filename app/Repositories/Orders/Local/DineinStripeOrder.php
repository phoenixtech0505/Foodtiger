<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasStripe;
use App\Traits\Expedition\HasDinein;

class DineinStripeOrder extends LocalOrderRepository
{
    use HasDinein;
    use HasStripe;
}
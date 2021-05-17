<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasPayPal;
use App\Traits\Expedition\HasDinein;

class DineinPayPalOrder extends LocalOrderRepository
{
    use HasDinein;
    use HasPayPal;
}
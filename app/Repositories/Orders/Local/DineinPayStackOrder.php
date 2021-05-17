<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasPayStack;
use App\Traits\Expedition\HasDinein;

class DineinPayStackOrder extends LocalOrderRepository
{
    use HasDinein;
    use HasPayStack;
}
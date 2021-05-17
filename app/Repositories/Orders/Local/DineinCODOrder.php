<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasCOD;
use App\Traits\Expedition\HasDinein;

class DineinCODOrder extends LocalOrderRepository
{
    use HasDinein;
    use HasCOD;
}
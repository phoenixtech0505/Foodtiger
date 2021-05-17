<?php

namespace App\Repositories\Orders\Local;
use App\Repositories\Orders\LocalOrderRepository;
use App\Traits\Payments\HasMollie;
use App\Traits\Expedition\HasDinein;

class DineinMollieOrder extends LocalOrderRepository
{
    use HasDinein;
    use HasMollie;
}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'name', 'code', 'restaurant_id', 'type', 'price', 'active_from', 'active_to', 'limit_to_num_uses',
    ];
}

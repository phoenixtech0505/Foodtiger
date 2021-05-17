<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function order()
    {
        return $this->belongsTo(\App\Order::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'name', 'table_id', 'phone_number', 'email', 'note', 'restaurant_id', 'by',
    ];

    public function table()
    {
        return $this->belongsTo(\App\Tables::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'address', 'lat', 'lng', 'apartment', 'intercom', 'floor', 'entry',
    ];

    protected $table = 'address';
}

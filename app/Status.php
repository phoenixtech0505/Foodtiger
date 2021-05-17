<?php

namespace App;

use App\Status;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    public $timestamps = false;
    protected $fillable = [
        'name', 'alias',
    ];
}

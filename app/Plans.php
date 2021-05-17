<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plans extends Model
{
    use SoftDeletes;
    protected $table = 'plan';
}

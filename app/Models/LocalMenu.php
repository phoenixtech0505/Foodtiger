<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalMenu extends Model
{
    use HasFactory;
    protected $fillable = ['restaurant_id', 'language', 'languageName', 'default'];

    protected $table = 'localmenus';
}

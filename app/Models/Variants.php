<?php

namespace App\Models;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variants extends Model
{
    use SoftDeletes;
    protected $table = 'variants';
    protected $fillable = ['price', 'item_id', 'options'];

    public function item()
    {
        return $this->belongsTo(\App\Items::class);
    }

    public function getOptionsListAttribute()
    {
        return implode(',', is_array(json_decode($this->options, true)) ? json_decode($this->options, true) : [__('No options values selected')]);
    }

    public function extras()
    {
        return $this->belongsToMany(\App\Extras::class, 'variants_has_extras', 'variant_id', 'extra_id');
    }
}

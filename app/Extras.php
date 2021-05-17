<?php

namespace App;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\TranslateAwareModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extras extends TranslateAwareModel
{
    use SoftDeletes;
    protected $table = 'extras';
    public $translatable = ['name'];

    public function item()
    {
        return $this->hasOne(\App\Items::class, 'id', 'item_id');
    }

    public function variants()
    {
        return $this->belongsToMany(\App\Models\Variants::class, 'variants_has_extras', 'extra_id', 'variant_id');
    }
}

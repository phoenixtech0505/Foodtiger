<?php

namespace App\Models;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\TranslateAwareModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Options extends TranslateAwareModel
{
    use SoftDeletes;
    protected $table = 'options';
    protected $fillable = ['name', 'options', 'item_id'];
    public $translatable = ['name'];

    public function item()
    {
        return $this->belongsTo(\App\Items::class);
    }
}

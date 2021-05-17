<?php

namespace App;

use App\Models\TranslateAwareModel;
use Illuminate\Database\Eloquent\Model;

class Categories extends TranslateAwareModel
{
    protected $table = 'categories';
    public $translatable = ['name'];

    public function items()
    {
        return $this->hasMany(\App\Items::class, 'category_id', 'id');
    }

    public function restorant()
    {
        return $this->belongsTo(\App\Restorant::class);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function (self $categories) {
            //Delete items
            foreach ($categories->items()->get() as $key => $item) {
                $item->forceDelete();
            }

            return true;
        });
    }
}

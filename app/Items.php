<?php

namespace App;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Models\TranslateAwareModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends TranslateAwareModel
{
    use SoftDeletes;
    public $translatable = ['name', 'description'];

    protected $table = 'items';
    protected $appends = ['logom', 'icon', 'short_description'];
    protected $fillable = ['name', 'description', 'image', 'price', 'category_id', 'vat'];
    protected $imagePath = '/uploads/restorants/';

    protected function getImge($imageValue, $default, $version = '_large.jpg')
    {
        if ($imageValue == '' || $imageValue == null) {
            //No image
            return $default;
        } else {
            if (strpos($imageValue, 'http') !== false) {
                //Have http
                if (strpos($imageValue, '.jpg') !== false || strpos($imageValue, '.jpeg') !== false || strpos($imageValue, '.png') !== false) {
                    //Has extension
                    return $imageValue;
                } else {
                    //No extension
                    return $imageValue.$version;
                }
            } else {
                //Local image
                return ($this->imagePath.$imageValue).$version;
            }
        }
    }

    public function substrwords($text, $chars, $end = '...')
    {
        if (strlen($text) > $chars) {
            $text = $text.' ';
            $text = substr($text, 0, $chars);
            $text = substr($text, 0, strrpos($text, ' '));
            $text = $text.'...';
        }

        return $text;
    }

    public function getLogomAttribute()
    {
        return $this->getImge($this->image, config('global.restorant_details_image'));
    }

    public function getIconAttribute()
    {
        return $this->getImge($this->image, config('global.restorant_details_image'), '_thumbnail.jpg');
    }

    public function getItempriceAttribute()
    {
        return  Money($this->price, config('settings.cashier_currency'), config('settings.do_convertion'))->format();
    }

    public function getShortDescriptionAttribute()
    {
        return  $this->substrwords($this->description, 40);
    }

    public function category()
    {
        return $this->belongsTo(\App\Categories::class);
    }

    public function extras()
    {
        return $this->hasMany(\App\Extras::class, 'item_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(\App\Models\Options::class, 'item_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(\App\Models\Variants::class, 'item_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($model) {
            if ($model->isForceDeleting()) {
                //dd('Force delete');

                //Delete Options
                $model->options()->forceDelete();

                //Deletee Variants
                foreach ($model->variants()->get() as $key => $variant) {
                    $variant->extras()->detach();
                }
                $model->variants()->forceDelete();

                //Delete extras
                $model->extras()->forceDelete();
            } else {
                //dd('Soft delete');
            }

            return true;
        });
    }
}

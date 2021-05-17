<?php

namespace App;

use App\MyModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends MyModel
{
    use SoftDeletes;
    protected $table = 'cities';
    protected $imagePath = '/uploads/settings/';

    protected $fillable = ['name', 'alias', 'image', 'header_title', 'header_subtitle'];
    protected $appends = ['logo'];

    public function getLogoAttribute()
    {
        return $this->getImge($this->image, config('global.restorant_details_image'));
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function (self $city) {
            if (config('settings.is_demo') | config('settings.is_demo')) {
                return false; //In demo disable deleting
            } else {
                return true;
            }
        });
    }
}

<?php

namespace App;

use App\SmsVerification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Twilio\Rest\Client;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'api_token', 'birth_date', 'working', 'lat', 'lng', 'numorders', 'rejectedorders',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function getAcceptanceratingAttribute()
    {
        if ($this->numorders == 0) {
            return 'No orders';
        } else {
            return round(((1 - ($this->rejectedorders / $this->numorders)) * 100), 2);
        }
    }

    public function restorant()
    {
        return $this->hasOne(\App\Restorant::class);
    }

    public function plan()
    {
        return $this->hasOne(\App\Plans::class, 'id', 'plan_id');
    }

    public function mplanid()
    {
        return $this->plan_id ? $this->plan_id : intval(config('settings.free_pricing_id'));
    }

    public function addresses()
    {
        return $this->hasMany(\App\Address::class)->where(['address.active' => 1]);
    }

    public function paths()
    {
        return $this->hasMany(\App\Paths::class, 'user_id', 'id')->where('created_at', '>=', Carbon::now()->subHours(2));
    }

    public function orders()
    {
        return $this->hasMany(\App\Order::class, 'client_id', 'id');
    }

    public function driverorders()
    {
        return $this->hasMany(\App\Order::class, 'driver_id', 'id');
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => [$this->id.'']];
    }

    public function routeNotificationForTwilio()
    {
        return $this->phone;
    }

    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function callToVerify()
    {
        $code = random_int(100000, 999999);
        $this->forceFill(['verification_code' => $code])->save();
        $client = new Client(config('settings.twilio_sid'), config('settings.twilio_auth_token'));
        $body = __('Hi').' '.$this->name.".\n\n".__('Your verification code is').': '.$code;
        $client->messages->create($this->phone, ['from' => config('settings.twilio_from'), 'body' => $body]);
    }
}

<?php

namespace App\Traits\Payments;
use Illuminate\Support\Facades\Validator;

trait HasCOD
{
    public function paymentRules(){
        return [];
    }

    public function payOrder(){
        //In COD there is no payment - Return empty validator
        return Validator::make([], []);
    }
}

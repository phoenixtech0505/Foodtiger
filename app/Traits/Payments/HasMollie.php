<?php

namespace App\Traits\Payments;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Facades\Validator;

trait HasMollie
{
    public function paymentRules(){
        return [];
    }

    public function payOrder(){
        try {
            $this->paymentRedirect= Mollie::api()->payments->create([
                'amount' => [
                    'currency' => config('settings.cashier_currency'),
                    'value' => number_format((float) $this->order->order_price+$this->order->delivery_price, 2, '.', ''),
                ],
                'description' => 'Order #'.$this->order->id,
                'redirectUrl' => route('orders.index'),
                'webhookUrl' => route('webhooks.mollie'),
                'metadata' => ['order_id' => $this->order->id],
            ])->getCheckoutUrl();
        } catch (ApiException $e) {
            if(config('app.debug') == true){
                dd($e);
            }
            $this->invalidateOrder();
            return Validator::make(['mollie_error_action'=>null], ['mollie_error_action'=>'required']);
        }
            

        //All ok
        return Validator::make([], []);
    }
}

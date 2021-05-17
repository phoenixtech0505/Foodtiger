<?php

namespace App\Repositories\Orders;
use Mollie\Api\Exceptions\ApiException;

class OrderRepoGenerator extends BaseOrderRepository
{

    public static function makeOrderRepo($vendor_id,$request,$expedition,$hasPayment,$isStripe,$isMobile=false){

        //Find the type
        $serviceType=$isMobile?"MobileApp":"WebService"; //FT
        if(config('app.isqrsaas')){
            if(config('settings.is_whatsapp_ordering_mode') ||config('settings.is_facebook_ordering_mode')){
                $serviceType="Social";//Whatsapp and FB
            }else{
                $serviceType="Local";//QR
            }
        }

        //Expedition
        $expeditionType="Delivery";
        if($expedition=="pickup"){
            $expeditionType="Pickup";
        }else if($expedition=="dinein"){
            $expeditionType="Dinein";
        }

        //Payment
        $paymentType="COD";
        if($hasPayment){
            if($isStripe){
                $paymentType="Stripe";
            }else if($request->payment_method=="paypal"){
                $paymentType="PayPal";
            }else if($request->payment_method=="mollie"){
                $paymentType="Mollie";
            }
        }

        //Class
        $generatedClass='App\Repositories\Orders\\'.$serviceType."\\".$expeditionType.$paymentType."Order";
        return new $generatedClass($vendor_id,$request,$expedition,$hasPayment,$isStripe);
    }
}
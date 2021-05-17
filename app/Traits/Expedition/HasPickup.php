<?php

namespace App\Traits\Expedition;

trait HasPickup
{
    public function expeditionRules(){
        return ['timeslot' =>['required']];
    }


    public function setAddressAndApplyDeliveryFee(){
        //In case we have phone, save it
        if($this->request->has('phone')){
            $this->order->phone= $this->request->phone;
        }
        $this->order->delivery_price= 0;
        $this->order->update();
    }

    public function setTimeSlot(){
        $this->order->delivery_pickup_interval=$this->request->timeslot;
        $this->order->update();
    }

    


}

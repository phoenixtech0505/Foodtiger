<?php

namespace App\Traits\Expedition;

use App\Address;

trait HasDelivery
{
    public function expeditionRules(){
        return [
            'address_id' => ['required'],
            'timeslot' =>['required']
        ];
    }

    public function setAddressAndApplyDeliveryFee(){
            //Set address
            $this->order->address_id=$this->request->address_id;

            //Calculate delivery cost
            $addresss = Address::findOrFail($this->request->address_id);
            $addressesWithFees = $this->getAccessibleAddresses($this->vendor, [$addresss]);
            
            $cost_total=0;
            foreach ($addressesWithFees as $key => $addressWithFee) {
                $cost_total=$addressWithFee->cost_total;
            }
            $this->order->delivery_price= $cost_total;
            $this->order->update();
    }

    public function setTimeSlot(){
        $this->order->delivery_pickup_interval=$this->request->timeslot;
        $this->order->update();
    }
}

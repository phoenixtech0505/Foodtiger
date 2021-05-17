<?php

/*
|--------------------------------------------------------------------------
| FoodTiger Web Order
|--------------------------------------------------------------------------
*/

namespace App\Repositories\Orders;

use Illuminate\Support\Facades\Validator;
use Cart;

class WebServiceOrderRepository extends BaseOrderRepository implements OrderTypeInterface
{

    public function validateData(){
        $validator=Validator::make($this->request->all(), array_merge($this->expeditionRules(),$this->paymentRules()));
        if($validator->fails()){$this->status=false;}
        return $validator;
    }

    public function makeOrder(){
        //From Parent - Construct the order
        $this->constructOrder();

        //From trait - set fee and time slot
        $this->setAddressAndApplyDeliveryFee();
        $this->setTimeSlot();

        //From parent - check if order is ok - min price etc.
        $resultFromValidateOrder=$this->validateOrder();
        if($resultFromValidateOrder->fails()){return $resultFromValidateOrder;}
        
        //From trait - make attempt to pay order or get payment link
        $resultFromPayOrder=$this->payOrder();
        if($resultFromPayOrder->fails()){return $resultFromPayOrder;}

        //Local - set Initial Status
        $this->setInitialStatus();

         //Local - clear cart
         $this->clearCart();

         //Local - Notify
         $this->notify();

        //At the end, return that all went ok
        return Validator::make([], []);
    }


    
    public function setInitialStatus(){
        //Set the just created status
        $this->order->status()->attach(1, ['user_id'=>auth()->user()->id, 'comment'=>'']);

        //If automatically approve, set the approved status also
        if (config('app.order_approve_directly')) {
            $this->order->status()->attach(2, ['user_id'=>1, 'comment'=>__('Automatically approved by admin')]);
        }
    }

    public function redirectOrInform(){
        if($this->status){
            //Success - redirect to success or to pay page
            return $this->paymentRedirect==null?redirect()->route('order.success', ['order' => $this->order]):redirect($this->paymentRedirect);
        }else{
            //There was some error, return back to the order page
            return redirect()->route('cart.checkout')->withInput();
        }
    }

    private function clearCart(){
        Cart::clear();
    }

    private function notify(){
        if (!config('app.order_approve_directly')) {
            //If we don't approve directly, we need to inform admin
            $this->notifyAdmin();
        }else{
            //In Case we approve directly, we need to inform owner
            $this->notifyOwner();
        }
    }
}
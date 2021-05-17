<?php

/*
|--------------------------------------------------------------------------
| QRSaaS Web Order
|--------------------------------------------------------------------------
*/

namespace App\Repositories\Orders;

use Illuminate\Support\Facades\Validator;
use Cart;
use Illuminate\Support\Facades\Cookie;

class LocalOrderRepository extends BaseOrderRepository implements OrderTypeInterface
{
    private $listOfOrders=null; //The list of order to go in the cookie

    public function validateData(){
        $validator=Validator::make($this->request->all(), array_merge($this->expeditionRules(),$this->paymentRules()));
        if($validator->fails()){$this->status=false;}
        return $validator;
    }

    public function makeOrder(){

        if($this->request->delivery_method=="dinein" && config('settings.disable_continius_ordering') && $this->request->payment_method == 'cod' ){
            //When we have dine in and we have enabled continues ordering, and this is COD order, we can have previous orders to that table
            $this->findMePreviousOrder();
        }

        //From Parent - Construct the order
        $this->constructOrder();

        //From trait - set fee and time slot
        $this->setAddressAndApplyDeliveryFee();
        $this->setTimeSlot();

        //From parent - check if order is ok - min price. -- Only for pickup - dine in should not have minimum
        if($this->request->delivery_method=="pickup"){
            $resultFromValidateOrder=$this->validateOrder();
            if($resultFromValidateOrder->fails()){return $resultFromValidateOrder;}
        }
        
        
        //From trait - make attempt to pay order or get payment link
        $resultFromPayOrder=$this->payOrder();
        if($resultFromPayOrder->fails()){return $resultFromPayOrder;}

        //Local - set Initial Status
        $this->setInitialStatus();

         //Local - clear cart
         $this->clearCart();

         //Local - Notify
         $this->notify();

         //Local - Store order is session
         $this->storeOrderInSession();

        //At the end, return that all went ok
        return Validator::make([], []);
    }


    
    public function setInitialStatus(){
        //Only if the order is new
        if($this->isNewOrder){
            //Set the just created status
            $this->order->status()->attach(1, ['user_id'=>$this->vendor->user->id, 'comment'=>'Local order']);

            //Set automatically approved by admin - since it it qr
            $this->order->status()->attach(2, ['user_id'=>1, 'comment'=>__('Automatically approved by admin')]);
        }
        
        
    }

    public function redirectOrInform(){
        if($this->status){
            if($this->paymentRedirect==null){
                //We don't have payment redirects
                if($this->isNewOrder){
                    //New order - redirect to success page
                    return redirect()->route('order.success', ['order' => $this->order])->withCookie(cookie('orders', $this->listOfOrders, 360));
                }else{
                    //Updated order - redirect to restaurant page
                    return redirect()
                        ->route('vendor', ['alias'=>$this->vendor->subdomain])
                        ->withCookie(cookie('orders', $this->listOfOrders, 360))
                        ->withStatus(__('Order updated.').' ID #'.$this->order->id);
                }
                
            }else{
                //We have payment redirect
                return redirect($this->paymentRedirect);
            }
        }else{
            //There was some error, return back to the order page
            return redirect()->route('cart.checkout')->withInput();
        }
    }

    private function clearCart(){
        Cart::clear();
    }

    private function notify(){
        $this->notifyOwner();
    }

    private function storeOrderInSession(){
        $previousOrders = Cookie::get('orders') ? Cookie::get('orders') : '';
        $previousOrderArray = array_filter(explode(',', $previousOrders));
        if($this->isNewOrder){
            //New order
            array_push($previousOrderArray, $this->order->id);
        }else{
            //Updated order
        }
        $this->listOfOrders = implode(',', $previousOrderArray);
    }
}
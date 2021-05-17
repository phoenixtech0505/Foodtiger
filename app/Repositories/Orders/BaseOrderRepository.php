<?php

namespace App\Repositories\Orders;
use App\Order;
use App\Restorant as Vendor;
use App\Items;
use App\Models\Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Notifications\OrderNotification;
use App\Events\NewOrder as PusherNewOrder;

class BaseOrderRepository extends Controller
{

    /**
     * @var Request request - The request made
     */
    public $request;

    /**
     * @var Vendor vendor - The vendor
     */
    public $vendor;

    /**
     * @var Order order - The order
     */
    public $order;

    /**
     * @var string expedition - Deliver - 1, PickUp -2, Dine in -3
     */
    public $expedition;

    /**
     * @var bool hasPayment
     */
    public $hasPayment;

    /**
     * @var bool isStripe
     */
    public $isStripe;

    /**
     * @var bool status
     */
    public $status=true;

    /**
     * @var bool isNewOrder
     */
    public $isNewOrder=true;

    /**
     * @var string errorMessage - Deliver, DineIn, PickUp
     */
    public $errorMessage="";

    /**
     * @var Redirect paymentRedirect
     */
    public $paymentRedirect=null;

     /**
     * @var bool isMobileOrder
     */
    public $isMobileOrder=false;


    /**
     * @var string redirectLink
     */
    public $redirectLink;

    public function __construct($vendor_id,$request,$expedition,$hasPayment,$isStripe){
        $this->request=$request;
        $this->expedition=$expedition;
        $this->hasPayment=$hasPayment;
        $this->isStripe=$isStripe;

        //Set the Vendor
        $this->vendor = Vendor::findOrFail($vendor_id);
    }

    

    public function constructOrder(){
        //Create the order 
        $this->createOrder();

        //Set Items
        $this->setItems();

        //Set Comment
        $this->setComment();

        //Calculate fees
        $this->calculateFees();

    }

    public function validateOrder(){
        $validator = Validator::make(['order_price'=>$this->order->order_price], [
            'order_price'=>['numeric','min:'.$this->vendor->minimum]
        ]);
        if($validator->fails()){
            $this->invalidateOrder();
        }
        return $validator;
    }

    public function invalidateOrder(){
        $this->status=false;
        $this->order->delete();
    }

    public function updateOrder(){
        //Store it if not stored yet, otherwise update it
        $this->order->update();
    }

    public function finalizeOrder(){
    }

    private function createOrder(){
        if($this->order==null){
            $this->order=new Order;
            $this->order->restorant_id=$this->vendor->id;
            $this->order->comment="";
            $this->order->payment_method=$this->request->payment_method;
            $this->order->payment_status="unpaid";

            $expeditionsTypes=['delivery'=>1,'pickup'=>2,'dinein'=>3]; //1- delivery 2 - pickup 3-dinein
            $this->order->delivery_method=$expeditionsTypes[$this->expedition];  

            //Client
            if(auth()->user()){
                $this->order->client_id=auth()->user()->id;
            }

            //TODO Set initials like VAT, prices etc to 0
            $this->order->order_price=0;
            $this->order->vatvalue=0;

            //Save order
            $this->order->save();

        }else{
            //Order is already initialized - in case of continues ordering
            $this->isNewOrder=false;
        }
    }
    
    private function setItems(){
        /**
          "items":[{
                "id":1,
                "qty":2,
                "extrasSelected":[{"id":1},{"id":2}],
                "variant":1
              }],
         */
        foreach ($this->request->items as $key => $item) {

            
            //Obtain the item
            $theItem = Items::findOrFail($item['id']);

            //List of extras
            $extras = [];
            
            //The price of the item or variant
            $itemSelectedPrice = $theItem->price;

            //Find the variant
            $variantName = '';
            if ($item['variant']) {
                //Find the variant
                $variant = Variants::findOrFail($item['variant']);
                $itemSelectedPrice = $variant->price;
                $variantName = $variant->optionsList;
            }

           //Find the extras
            foreach ($item['extrasSelected'] as $key => $extra) {
                $theExtra = $theItem->extras()->findOrFail($extra['id']);
                $itemSelectedPrice+=$theExtra->price;
                array_push($extras, $theExtra->name.' + '.money($theExtra->price, config('settings.cashier_currency'), config('settings.do_convertion')));
            }
            
            //Total vat on this item
            $totalCalculatedVAT = $item['qty'] * ($theItem->vat > 0?$itemSelectedPrice * ($theItem->vat / 100):0);

            $this->order->items()->attach($item['id'], [
                'qty'=>$item['qty'], 
                'extras'=>json_encode($extras), 
                'vat'=>$theItem->vat, 
                'vatvalue'=>$totalCalculatedVAT, 
                'variant_name'=>$variantName, 
                'variant_price'=>$itemSelectedPrice
            ]);
        } 


        //After we have updated the list of items, we need to update the order price
        $order_price=0;
        $total_order_vat=0;
        foreach ($this->order->items()->get() as $key => $item) {
            $order_price+=$item->pivot->qty*$item->pivot->variant_price;
            $total_order_vat+=$item->pivot->qty*$item->pivot->vatvalue;
        }
        $this->order->order_price=$order_price;
        $this->order->vatvalue=$total_order_vat;

        //Update the order with the item
        $this->order->update();
    }

    private function setComment(){
       
        $comment = $this->request->comment ? strip_tags($this->request->comment.'') : '';
        $this->order->comment = $this->order->comment.' '.$comment;
        $this->order->update();
    }

    private function calculateFees(){
        $this->order->static_fee=$this->vendor->static_fee;
        $this->order->fee=$this->vendor->fee;
        $this->order->fee_value=($this->vendor->fee/100)*($this->order->order_price-$this->vendor->static_fee);
        $this->order->update();
    }

    public function notifyAdmin(){
        //Does nothing
    }

    public function notifyOwner(){
        //Inform owner - via email, sms or db
        $this->vendor->user->notify((new OrderNotification($this->order))->locale(strtolower(config('settings.app_locale'))));

        //Notify owner with pusher
        if (strlen(config('broadcasting.connections.pusher.secret')) > 4) {
            event(new PusherNewOrder($this->order, __('notications.notification_neworder')));
        }
    }
}
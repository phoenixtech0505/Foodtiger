<?php

namespace App\Http\Controllers;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Items;
use App\Models\Variants;
use App\Order;
use App\Restorant;
use App\Tables;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $item = Items::find($request->id);
        $restID = $item->category->restorant->id;

        $restaurant = Restorant::findOrFail($restID);
        \App\Services\ConfChanger::switchCurrency($restaurant);
        

        //Check if added item is from the same restorant as previus items in cart
        $canAdd = false;
        if (Cart::getContent()->isEmpty()) {
            $canAdd = true;
        } else {
            $canAdd = true;
            foreach (Cart::getContent() as $key => $cartItem) {
                if ($cartItem->attributes->restorant_id.'' != $restID.'') {
                    $canAdd = false;
                    break;
                }
            }
        }

        //TODO - check if cart contains, if so, check if restorant is same as pervios one

        // Cart::clear();
        if ($item && $canAdd) {

            //are there any extras
            $cartItemPrice = $item->price;
            $cartItemName = $item->name;
            $theElement = '';

            //Is there a varaint

            //variantID
            if ($request->variantID) {
                //Get the variant
                $variant = Variants::findOrFail($request->variantID);

                //Validate is this variant is from the current item
                if ($variant->item->id == $item->id) {
                    $cartItemPrice = $variant->price;

                    //For each option, find the option on the
                    $cartItemName = $item->name.' '.$variant->optionsList;
                    //$theElement.=$value." -- ".$item->extras()->findOrFail($value)->name."  --> ". $cartItemPrice." ->- ";
                }
            }

            foreach ($request->extras as $key => $value) {
                $cartItemName .= "\n+ ".$item->extras()->findOrFail($value)->name;
                $cartItemPrice += $item->extras()->findOrFail($value)->price;
                $theElement .= $value.' -- '.$item->extras()->findOrFail($value)->name.'  --> '.$cartItemPrice.' ->- ';
            }

            Cart::add((new \DateTime())->getTimestamp(), $cartItemName, $cartItemPrice, $request->quantity, ['id'=>$item->id, 'variant'=>$request->variantID, 'extras'=>$request->extras, 'restorant_id'=>$restID, 'image'=>$item->icon, 'friendly_price'=>  Money($cartItemPrice, config('settings.cashier_currency'), config('settings.do_convertion'))->format()]);

            return response()->json([
                'status' => true,
                'errMsg' => $theElement,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => __("You can't add items from other restaurant!"),
            ]);
            //], 401);
        }
    }

    public function getContent()
    {
        //Cart::clear();
        return response()->json([
            'data' => Cart::getContent(),
            'total' => Cart::getSubTotal(),
            'status' => true,
            'errMsg' => '',
        ]);
    }

    

   

    

    

    public function cart()
    {
        $restorantID = null;
        foreach (Cart::getContent() as $key => $cartItem) {
            $restorantID = $cartItem->attributes->restorant_id;
            break;
        }

        //The restaurant
        $restaurant = Restorant::findOrFail($restorantID);

        $enablePayments=true;
        if(config('app.isqrsaas')){
            if($restaurant->currency!=""&&$restaurant->currency!=config('settings.cashier_currency')){
                $enablePayments=false;
            }
           
        }

        //Change currency
        \App\Services\ConfChanger::switchCurrency($restaurant);

        //Create all the time slots
        $timeSlots = $restaurant->hours ? $this->getTimieSlots($restaurant->hours->toArray()) : [];

        //Working hours
        $ourDateOfWeek = date('N') - 1;

        $format = 'G:i';
        if (config('settings.time_format') == 'AM/PM') {
            $format = 'g:i A';
        }

        $openingTime = $restaurant->hours ? date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_from'])) : null;
        $closingTime = $restaurant->hours ? date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_to'])) : null;

        //user addresses
        $addresses = [];
        if (config('app.isft')) {
            $addresses = $this->getAccessibleAddresses($restaurant, auth()->user()->addresses->reverse());
        }

        $tables = Tables::where('restaurant_id', $restaurant->id)->get();
        $tablesData = [];
        foreach ($tables as $key => $table) {
            $tablesData[$table->id] = $table->restoarea ? $table->restoarea->name.' - '.$table->name : $table->name;
        }

       

        $params = [
            'enablePayments'=>$enablePayments,
            'title' => 'Shopping Cart Checkout',
            'tables' =>  ['ftype'=>'select', 'name'=>'', 'id'=>'table_id', 'placeholder'=>'Select table', 'data'=>$tablesData, 'required'=>true],
            'restorant' => $restaurant,
            'timeSlots' => $timeSlots,
            'openingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_from'] ? $openingTime : null,
            'closingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_to'] ? $closingTime : null,
            'addresses' => $addresses,
        ];

        //Open for all
        return view('cart')->with($params);
    }

    public function clear(Request $request)
    {

        //Get the client_id from address_id

        $oreder = new Order;
        $oreder->address_id = strip_tags($request->addressID);
        $oreder->restorant_id = strip_tags($request->restID);
        $oreder->client_id = auth()->user()->id;
        $oreder->driver_id = 2;
        $oreder->delivery_price = 3.00;
        $oreder->order_price = strip_tags($request->orderPrice);
        $oreder->comment = strip_tags($request->comment);
        $oreder->save();

        foreach (Cart::getContent() as $key => $item) {
            $oreder->items()->attach($item->id);
        }

        //Find first status id,
        ///$oreder->stauts()->attach($status->id,['user_id'=>auth()->user()->id]);
        Cart::clear();

        return redirect()->route('front')->withStatus(__('Cart clear.'));
        //return back()->with('success',"The shopping cart has successfully beed added to the shopping cart!");;
    }

    /**
     * Create a new controller instance.

     *

     * @return void
     */
    public function remove(Request $request)
    {
        Cart::remove($request->id);

        return response()->json([
            'status' => true,
            'errMsg' => '',
        ]);
    }

    /**
     * Makes general api resonse.
     */
    private function generalApiResponse()
    {
        return response()->json([
            'status' => true,
            'errMsg' => '',
        ]);
    }

    /**
     * Updates cart.
     */
    private function updateCartQty($howMuch, $item_id)
    {
        Cart::update($item_id, ['quantity' => $howMuch]);

        return $this->generalApiResponse();
    }

    /**
     * Increase cart.
     */
    public function increase($id)
    {
        return $this->updateCartQty(1, $id);
    }

    /**
     * Decrese cart.
     */
    public function decrease($id)
    {
        return $this->updateCartQty(-1, $id);
    }
}

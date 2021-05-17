<?php

namespace App\Http\Controllers\API\Client;

use App\Repositories\Orders\OrderRepoGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Status;
use App\User;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use App\Restorant;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Order::where('client_id', auth()->user()->id)->orderBy('id', 'DESC')->limit(50)->with(['restorant', 'status', 'items', 'address', 'driver'])->get(),
            'status' => true,
            'message' => '',
        ]);
    }

    public function store(Request $request){
 
        //Init validator
        $validatorOnInit = Validator::make($request->all(), ['vendor_id'=>['required']]);
        if ($validatorOnInit->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validatorOnInit->errors()->first(),
            ]);
        }


        //Data
        $mobileLikeRequest=$request;
        $vendor_id =  $mobileLikeRequest->vendor_id;
        $expedition= $mobileLikeRequest->delivery_method;
        $hasPayment= $mobileLikeRequest->payment_method!="cod";
        $isStripe= $mobileLikeRequest->payment_method=="stripe";

        //Repo Holder
        $orderRepo=OrderRepoGenerator::makeOrderRepo($vendor_id,$mobileLikeRequest,$expedition,$hasPayment,$isStripe,true);
    
        
        //Proceed with validating the data
        $validator=$orderRepo->validateData();
        if ($validator->fails()) { 
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        //Proceed with making the order
        $validatorOnMaking=$orderRepo->makeOrder();
        if ($validatorOnMaking->fails()) { 
            return response()->json([
                'status' => false,
                'message' => $validatorOnMaking->errors()->first(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order created',
            'id'=>$orderRepo->order->id,
            'paymentLink'=>$orderRepo->paymentRedirect
        ]);
         
    }
}

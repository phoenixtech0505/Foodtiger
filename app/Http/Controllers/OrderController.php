<?php

namespace App\Http\Controllers;

use App\Repositories\Orders\OrderRepoGenerator;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Address;
use App\Events\NewOrder;
use App\Exports\OrdersExport;
use App\Http\Controllers\CartController;
use App\Items;
use App\Models\Variants;
use App\Notifications\OrderNotification;
use App\Order;
use App\Restorant;
use App\Status;
use App\User;
use Carbon\Carbon;
use Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Maatwebsite\Excel\Facades\Excel;
use Mollie\Laravel\Facades\Mollie;
use PayPal\Api\Amount;
use PayPal\Api\Details;
//PayPal
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Unicodeveloper\Paystack\Paystack;
use URL;
use willvincent\Rateable\Rating;
use App\Services\ConfChanger;

class OrderController extends Controller
{
    public function migrateStatuses()
    {
        if (Status::count() < 13) {
            $statuses = ['Just created', 'Accepted by admin', 'Accepted by restaurant', 'Assigned to driver', 'Prepared', 'Picked up', 'Delivered', 'Rejected by admin', 'Rejected by restaurant', 'Updated', 'Closed', 'Rejected by driver', 'Accepted by driver'];
            foreach ($statuses as $key => $status) {
                Status::updateOrCreate(['name' => $status], ['alias' =>  str_replace(' ', '_', strtolower($status))]);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->migrateStatuses();

        $restorants = Restorant::where(['active'=>1])->get();
        $drivers = User::role('driver')->where(['active'=>1])->get();
        $clients = User::role('client')->where(['active'=>1])->get();

        $driversData = [];
        foreach ($drivers as $key => $driver) {
            $driversData[$driver->id] = $driver->name;
        }

        $orders = Order::orderBy('created_at', 'desc');

        //Get client's orders
        if (auth()->user()->hasRole('client')) {
            $orders = $orders->where(['client_id'=>auth()->user()->id]);
        ////Get driver's orders
        } elseif (auth()->user()->hasRole('driver')) {
            $orders = $orders->where(['driver_id'=>auth()->user()->id]);
        //Get owner's restorant orders
        } elseif (auth()->user()->hasRole('owner')) {
             
            //Change currency
            ConfChanger::switchCurrency(auth()->user()->restorant);

            $orders = $orders->where(['restorant_id'=>auth()->user()->restorant->id]);
        }

        //FILTER BT RESTORANT
        if (isset($_GET['restorant_id'])) {
            $orders = $orders->where(['restorant_id'=>$_GET['restorant_id']]);
        }
        //If restorant owner, get his restorant orders only
        if (auth()->user()->hasRole('owner')) {
            //Current restorant id
            $restorant_id = auth()->user()->restorant->id;
            $orders = $orders->where(['restorant_id'=>$restorant_id]);
        }

        //BY CLIENT
        if (isset($_GET['client_id'])) {
            $orders = $orders->where(['client_id'=>$_GET['client_id']]);
        }

        //BY DRIVER
        if (isset($_GET['driver_id'])) {
            $orders = $orders->where(['driver_id'=>$_GET['driver_id']]);
        }

        //BY DATE FROM
        if (isset($_GET['fromDate']) && strlen($_GET['fromDate']) > 3) {
            //$start = Carbon::parse($_GET['fromDate']);
            $orders = $orders->whereDate('created_at', '>=', $_GET['fromDate']);
        }

        //BY DATE TO
        if (isset($_GET['toDate']) && strlen($_GET['toDate']) > 3) {
            //$end = Carbon::parse($_GET['toDate']);
            $orders = $orders->whereDate('created_at', '<=', $_GET['toDate']);
        }

        //With downloaod
        if (isset($_GET['report'])) {
            $items = [];
            foreach ($orders->get() as $key => $order) {
                $item = [
                    'order_id'=>$order->id,
                    'restaurant_name'=>$order->restorant->name,
                    'restaurant_id'=>$order->restorant_id,
                    'created'=>$order->created_at,
                    'last_status'=>$order->status->pluck('alias')->last(),
                    'client_name'=>$order->client ? $order->client->name : '',
                    'client_id'=>$order->client ? $order->client_id : null,
                    'table_name'=>$order->table ? $order->table->name : '',
                    'table_id'=>$order->table ? $order->table_id : null,
                    'area_name'=>$order->table && $order->table->restoarea ? $order->table->restoarea->name : '',
                    'area_id'=>$order->table && $order->table->restoarea ? $order->table->restoarea->id : null,
                    'address'=>$order->address ? $order->address->address : '',
                    'address_id'=>$order->address_id,
                    'driver_name'=>$order->driver ? $order->driver->name : '',
                    'driver_id'=>$order->driver_id,
                    'order_value'=>$order->order_price,
                    'order_delivery'=>$order->delivery_price,
                    'order_total'=>$order->delivery_price + $order->order_price,
                    'payment_method'=>$order->payment_method,
                    'srtipe_payment_id'=>$order->srtipe_payment_id,
                    'order_fee'=>$order->fee_value,
                    'restaurant_fee'=>$order->fee,
                    'restaurant_static_fee'=>$order->static_fee,
                    'vat'=>$order->vatvalue,
                  ];
                array_push($items, $item);
            }

            return Excel::download(new OrdersExport($items), 'orders_'.time().'.xlsx');
        }

        $orders = $orders->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'restorants'=>$restorants,
            'drivers'=>$drivers,
            'fields'=>[['class'=>'col-12', 'classselect'=>'noselecttwo', 'ftype'=>'select', 'name'=>'Driver', 'id'=>'driver', 'placeholder'=>'Assign Driver', 'data'=>$driversData, 'required'=>true]],
            'clients'=>$clients,
            'parameters'=>count($_GET) != 0,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    

    private function toMobileLike(Request $request){
        /*{
            "restaurant_id":1,
            "delivery_method":"delivery", //delivery, pickup, dinein
            "payment_method":"cod" ,
            "address_id":1,
            "platform":"WebService",
            "items":[{
                "id":1,
                "qty":2,
                "extrasSelected":[{"id":1},{"id":2}],
                "variant":1
              }],
            "order_price":72,
            "comment":"",
            "timeslot":"1320_1350",
            "stripe_token":null
        }*/


        //Find vendor id
        $vendor_id = null;
        foreach (Cart::getContent() as $key => $item) {
            $vendor_id = $item->attributes->restorant_id;
        }

        //Organize the item
        $items=[];
        foreach (Cart::getContent() as $key => $item) {
            $extras=[];
            foreach ($item->attributes->extras as $keyExtra => $extra_id) {
                array_push($extras,array('id'=>$extra_id));
            }
            array_push($items,array(
                "id"=>$item->attributes->id,
                "qty"=>$item->quantity,
                "variant"=>$item->attributes->variant,
                "extrasSelected"=>$extras
            ));
        }


        //stripe token
        $stripe_token=null;
        if($request->has('stripePaymentId')){
            $stripe_token=$request->stripePaymentId;
        }

        //Default - pickup - since available everywhere
        $delivery_method="pickup";
        
        //Delivery method - deliveryType - ft
        if($request->has('deliveryType')){
            $delivery_method=$request->deliveryType;
        }

        //Delivery method  - dineType - qr
        if($request->has('dineType')){
            $delivery_method=$request->dineType;
        }

        //takeaway is pickup
        if($delivery_method=="takeaway"){
            $delivery_method="pickup";
        }

        //Table id
        $table_id=null;
        if($request->has('table_id')){
            $table_id=$request->table_id;
        }

         //Phone 
         $phone=null;
         if($request->has('phone')){
             $phone=$request->phone;
         }
       

        $requestData=[
            'vendor_id'   => $vendor_id,
            'delivery_method'=> $delivery_method,
            'payment_method'=> $request->paymentType,
            'address_id'=>$request->addressID,
            "timeslot"=>$request->timeslot,
            "items"=>$items,
            "comment"=>$request->comment,
            "stripe_token"=>$stripe_token,
            "dinein_table_id"=>$table_id,
            "phone"=>$phone
        ];

        return new Request($requestData);
    }

    public function store(Request $request){

        //Convert web request to mobile like request
        $mobileLikeRequest=$this->toMobileLike($request);

        //Data
        $vendor_id =  $mobileLikeRequest->vendor_id;
        $expedition= $mobileLikeRequest->delivery_method;
        $hasPayment= $mobileLikeRequest->payment_method!="cod";
        $isStripe= $mobileLikeRequest->payment_method=="stripe";

        //Repo Holder
        $orderRepo=OrderRepoGenerator::makeOrderRepo($vendor_id,$mobileLikeRequest,$expedition,$hasPayment,$isStripe);

        //Proceed with validating the data
        $validator=$orderRepo->validateData();
        if ($validator->fails()) { 
            notify()->error($validator->errors()->first());
            return $orderRepo->redirectOrInform(); 
        }

        //Proceed with making the order
        $validatorOnMaking=$orderRepo->makeOrder();
        if ($validatorOnMaking->fails()) { 
            notify()->error($validatorOnMaking->errors()->first()); 
            return $orderRepo->redirectOrInform(); 
        }

        return $orderRepo->redirectOrInform();
    }

    public function store_old(Request $request)
    {
        $redirect_url = null;
        $restorant_id = null;
        foreach (Cart::getContent() as $key => $item) {
            $restorant_id = $item->attributes->restorant_id;
        }

        $restorant = Restorant::findOrFail($restorant_id);

        $orderPrice = Cart::getSubTotal();

        //Check if deliveryType exeist
        if ($request->exists('deliveryType')) {
            $isDelivery = $request->deliveryType == 'delivery';
        } else {
            //Defauls is delivery if ft
            $isDelivery = config('app.isft');
        }

        //Check if dine in
        $isTakeAway = null;
        if ($request->exists('dineType')) {
            $isDineIn = $request->dineType == 'dinein';
            $isTakeAway = $request->dineType == 'takeaway';
        } else {
            //Default is delivery if qr
            $isDineIn = config('app.isqrsaas');
        }

        //If delivery, address is required
        if ($isDelivery && ! $request->addressID) {
            return redirect()->route('cart.checkout')->withError(__('Please select address first.'))->withInput();
        }

        $restorant_fee = Restorant::select('fee', 'static_fee')->where(['id'=>$restorant_id])->get()->first();
        //Commision fee
        //$restorant_fee = Restorant::select('fee')->where(['id'=>$restorant_id])->value('fee');
        $order_fee = ($restorant_fee->fee / 100) * ($orderPrice - $restorant_fee->static_fee);

        //Else, based on table id, decide if new, or use existing order
        $isOldLocalOrder = false;

        //Create
        if (! config('settings.is_whatsapp_ordering_mode') && ! config('settings.is_facebook_ordering_mode')) {
            if (config('app.isqrsaas') && $isDineIn && ! config('settings.disable_continius_ordering') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering')) {
                if (! $request->table_id) {
                    return redirect()->route('cart.checkout')->withError(__('Please select table.'))->withInput();
                }

                //1. Get the last order for this table
                $lastOrdereForTable = Order::where('table_id', $request->table_id)->where('payment_status', 'unpaid')->where('payment_method', 'cod')->latest('id')->first();

                //2. Check if the status is 1 - jc, 3 -accepted, 5-prepared, 7- delivered, 10 - updated, and unpaid
                if ($lastOrdereForTable != null) {
                    $isOldLocalOrder = in_array($lastOrdereForTable->status->pluck('id')->last(), [1, 3, 5, 7, 10]) && $request->paymentType == 'cod';
                }

                //3. If ready for update, set flaag, and make oder
                if (! $isOldLocalOrder) {
                    $order = new Order;
                } else {
                    $order = $lastOrdereForTable;
                }
            } elseif (config('app.isqrsaas') && $isTakeAway && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering')) {
                if (! $request->phone) {
                    return redirect()->route('cart.checkout')->withError(__('Please enter phone number.'))->withInput();
                }

                $order = new Order;
            } else {
                //FT - each request is new oorder
                $order = new Order;
            }
        } else {
            $order = new Order;
        }

        if ($isDelivery && ! config('settings.is_whatsapp_ordering_mode') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering') && ! config('settings.is_facebook_ordering_mode')) {
            $order->address_id = $request->addressID;
        } else {
            $order->whatsapp_address = $request->addressID;
        }

        $order->restorant_id = $restorant_id;
        if (config('app.isqrsaas') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering') && ! config('settings.is_facebook_ordering_mode')) {
            //Use table id, if dine in
            if ($isDineIn) {
                $order->table_id = $request->table_id;
            } else {
                //This is pickup
                $order->phone = $request->phone;
            }
        } elseif (! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering')) {
            //Use client
            $order->client_id = auth()->user()->id;
        }

        if (config('settings.is_whatsapp_ordering_mode') || config('settings.whatsapp_ordering_enabled') || config('settings.enable_facebook_ordering') || config('settings.is_facebook_ordering_mode')) {
            $order->delivery_price = 0;
        } else {
            $order->delivery_price = $isDelivery ? $request->deliveryCost : 0;
        }

        if ($isOldLocalOrder) {
            $order->order_price = $order->order_price + $orderPrice;
            $newcomment = $request->comment ? strip_tags($request->comment.'') : '';
            $order->comment = $order->comment.' '.$newcomment;
        } else {
            $order->order_price = $orderPrice;
            $order->comment = $request->comment ? strip_tags($request->comment.'') : '';
        }

        $order->payment_method = config('settings.is_whatsapp_ordering_mode') ? 'Whatsapp order' : $request->paymentType;
        $order->fee = $restorant_fee->fee;
        $order->fee_value = $order_fee;
        $order->static_fee = $restorant_fee->static_fee;
        $order->delivery_method = $isDelivery ? 1 : ($isDineIn ? 3 : 2);  //1- delivery 2 - pickup 3-Local
        if ($request->exists('timeslot')) {
            $order->delivery_pickup_interval = $request->timeslot;
        }

        $restorant_min_order = $restorant->minimum;
        if (floatval($restorant_min_order) >= $order->order_price) {
            //We have problem, minimum order is not reached
            return redirect()->route('cart.checkout')->withError(__('The minimmum order value is').': '.money(floatval($restorant_min_order), config('settings.cashier_currency'), true))->withInput();
        }

        //Stripe payment
        if ($request->paymentType == 'stripe') {
            //Make the payment

            $total_price = (int) (($orderPrice) * 100);
            if ($isDelivery) {
                $total_price = (int) (($orderPrice + $request->deliveryCost) * 100);
            }

            try {
                $chargeOptions = [];
                if (config('settings.enable_stripe_connect') && $restorant->user->stripe_account) {
                    $application_fee_amount = 0;

                    //Delivery fee
                    if ($isDelivery) {
                        $application_fee_amount += (int) (($request->deliveryCost));
                    }

                    //Static fee
                    $application_fee_amount += (float) $restorant->static_fee;

                    //Percentage fee
                    $application_fee_amount += (float) (($orderPrice - $restorant->static_fee) / 100) * $restorant->fee;

                    //Make it for stripe
                    $application_fee_amount = (int) (float) ($application_fee_amount * 100);

                    //Create the charge object
                    $chargeOptions = [
                        'application_fee_amount' => $application_fee_amount,
                        'transfer_data' => [
                            'destination' => $restorant->user->stripe_account.'',
                        ],
                    ];
                }
                //dd([$total_price, $request->stripePaymentId,$chargeOptions]);

                //If user is logged in, chanrge logged in user
                if (auth()->user()) {
                    $payment_stripe = auth()->user()->charge($total_price, $request->stripePaymentId, $chargeOptions);
                } else {
                    //Otherwise make charge like restaurant owner did it
                    $payment_stripe = $restorant->user->charge($total_price, $request->stripePaymentId, $chargeOptions);
                }

                $order->srtipe_payment_id = $payment_stripe->id;
                $order->payment_status = 'paid';
                $order->payment_processor_fee = ((($orderPrice + $order->delivery_price) / 100) * config('settings.stripe_fee')) + config('settings.stripe_static_fee');
                $order->save();
                //dd($payment_stripe);
            } catch (PaymentActionRequired $e) {
                return redirect()->route('cart.checkout')->withError('The payment attempt failed because additional action is required before it can be completed.')->withInput();
            }
        } elseif ($request->paymentType == 'paystack') {
            // computed amount -> $amount;
            $total_price = $orderPrice;
            if ($isDelivery) {
                $total_price = $orderPrice + $request->deliveryCost;
            }

            $application_fee_amount = 0;

            //Delivery fee
            if ($isDelivery) {
                $application_fee_amount += (int) (($request->deliveryCost));
            }

            //Static fee
            $application_fee_amount += (float) $restorant->static_fee;

            //Percentage fee
            $application_fee_amount += (float) (($orderPrice - $restorant->static_fee) / 100) * $restorant->fee;

            //Make it for stripe
            $application_fee_amount = (int) (float) ($application_fee_amount * 100);

            $quantity = 0;
            foreach (Cart::getContent() as $key => $item) {
                $quantity += $item->quantity;
            }

            $order->srtipe_payment_id = null;
            $order->payment_status = 'unpaid';
            $order->payment_processor_fee = 0;
            $order->save();

            try {
                $paystack = new Paystack();
                $user = auth()->user();
                $request->email = auth()->user()->email;
                $request->orderID = $order->id;
                $request->metadata = json_encode($array = [
                    'order_id' => $order->id,
                    'restorant_id' => $restorant_id,
                ]);
                $request->amount = $total_price;
                $request->quantity = $quantity;
                $request->reference = $paystack->genTranxRef();
                $request->key = config('paystack.secretKey');

                $redirect_url = $paystack->getAuthorizationUrl();
                //return $paystack->getAuthorizationUrl()->redirectNow();
            } catch (\Exception $e) {
                return redirect()->route('cart.checkout')->withMesswithErrorage('The paystack token has expired. Please refresh the page and try again.')->withInput();
            }
        } elseif ($request->paymentType == 'paypal') {
            $order->srtipe_payment_id = null;
            $order->payment_status = 'unpaid';
            $order->payment_processor_fee = 0;
            $order->save();

            $total_price = $orderPrice;
            if ($isDelivery) {
                $total_price = $orderPrice + $request->deliveryCost;
            }

            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    config('services.paypal.client_id'),
                    config('services.paypal.secret')
                )
            );
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $itemsArr = [];
            foreach (Cart::getContent() as $key => $item) {
                $itemObj = new Item();

                $itemObj->setName($item->name)
                    ->setCurrency(strtoupper(config('settings.cashier_currency')))
                    ->setQuantity(intval($item->quantity))
                    ->setSku(strval($item->id)) // Similar to `item_number` in Classic API
                    ->setPrice($item->price);

                array_push($itemsArr, $itemObj);
            }

            $itemList = new ItemList();
            $itemList->setItems($itemsArr);

            /*$details = new Details();
            $details->setShipping(1.2)
                ->setTax(1.3)
                ->setSubtotal(17.50);*/

            $amount = new Amount();
            $amount->setCurrency(strtoupper(config('settings.cashier_currency')))
                ->setTotal($total_price);
            //->setDetails($details);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription('Payment description')
                //->setInvoiceNumber(uniqid());
                ->setInvoiceNumber($order->id);

            //$baseUrl = getBaseUrl();
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(URL::to('/').'/execute-payment-pp?success=true')
                ->setCancelUrl(URL::to('/').'/cancel');

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $payment->create($apiContext);

                if (isset($redirectUrls)) {
                    //return redirect($payment->getApprovalLink());
                    $redirect_url = $payment->getApprovalLink();
                }
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                //echo $ex->getData();
                return redirect()->route('cart.checkout')->withMesswithErrorage('The payment attempt failed because additional action is required before it can be completed.')->withInput();
            }
        } elseif ($request->paymentType == 'mollie') {
            $order->srtipe_payment_id = null;
            $order->payment_status = 'unpaid';
            $order->payment_processor_fee = 0;
            $order->save();

            $total_price = $orderPrice;
            if ($isDelivery) {
                $total_price = $orderPrice + $request->deliveryCost;
            }

            $payment = Mollie::api()->payments->create([
                'amount' => [
                    'currency' => config('settings.cashier_currency'),
                    //"value" => "10.00" // You must send the correct number of decimals, thus we enforce the use of strings
                    'value' => number_format((float) $total_price, 2, '.', ''),
                ],
                'description' => 'Order #'.$order->id,
                'redirectUrl' => route('orders.index'),
                'webhookUrl' => route('webhooks.mollie'),
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            $redirect_url = $payment->getCheckoutUrl();
        //redirect customer to Mollie checkout page
            //return redirect($payment->getCheckoutUrl(), 303);
        } else {
            $order->srtipe_payment_id = null;
            $order->payment_status = 'unpaid';
            $order->payment_processor_fee = 0;

            if ($isOldLocalOrder) {
                $order->update();
            } elseif (config('settings.is_whatsapp_ordering_mode')) {
                $order->save();
            } else {
                $order->save();
            }
        }

        $totalCalculatedVAT = 0;

        //TODO - Create items
        foreach (Cart::getContent() as $key => $item) {
            $calculatedVAT = 0;
            //Create the extras
            $extras = [];
            $theItem = Items::findOrFail($item->attributes->id);

            $itemSelectedPrice = $theItem->price;
            $variantName = '';
            if ($item->attributes->variant) {
                //Find the variant
                $variant = Variants::findOrFail($item->attributes->variant);
                $itemSelectedPrice = $variant->price;
                $variantName = $variant->optionsList;
            }

            if ($theItem->vat > 0) {
                $calculatedVAT = $itemSelectedPrice * ($theItem->vat / 100);
            }
            foreach ($item->attributes->extras as $key => $extraID) {
                $theExtra = $theItem->extras()->findOrFail($extraID);
                if ($theItem->vat > 0) {
                    $calculatedVAT += $theExtra->price * ($theItem->vat / 100);
                }
                array_push($extras, $theExtra->name.' + '.money($theExtra->price, config('settings.cashier_currency'), config('settings.do_convertion')));
            }
            //dd($extras);
            $totalCalculatedVAT += $item->quantity * $calculatedVAT;
            $order->items()->attach($item->attributes->id, ['qty'=>$item->quantity, 'extras'=>json_encode($extras), 'vat'=>$theItem->vat, 'vatvalue'=>$item->quantity * $calculatedVAT, 'variant_name'=>$variantName, 'variant_price'=>$itemSelectedPrice]);
        }

        //Set order vat
        if ($isOldLocalOrder) {
            $order->vatvalue = $order->vatvalue + $totalCalculatedVAT;
        } else {
            $order->vatvalue = $totalCalculatedVAT;
        }

        $order->update();

        //Create status
        if (config('app.isqrsaas') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering')) {
            //Status IN QR
            if ($isOldLocalOrder) {
                $status = Status::find(10);
                $order->status()->attach($status->id, ['user_id'=>$restorant->user_id, 'comment'=>'Local ordering updated']);
            } else {
                $status = Status::find(1);
                $order->status()->attach($status->id, ['user_id'=>$restorant->user_id, 'comment'=>'Local ordering']);
            }
        } elseif (config('settings.is_whatsapp_ordering_mode') || config('settings.whatsapp_ordering_enabled') || config('settings.enable_facebook_ordering') || config('settings.is_facebook_ordering_mode')) {
            $status = Status::find(1);

            $comment = null;
            if (config('settings.is_whatsapp_ordering_mode') || config('settings.whatsapp_ordering_enabled')) {
                $comment = 'Whatsapp order';
            } else {
                $comment = 'Facebook order';
            }

            $order->status()->attach($status->id, ['user_id'=>$restorant->user_id, 'comment'=>$comment]);
        } else {
            //Status in FT
            $status = Status::find(1);
            $order->status()->attach($status->id, ['user_id'=>auth()->user()->id, 'comment'=>'']);

            //If approve directly
            if (config('app.order_approve_directly')) {
                $status = Status::find(2);
                $order->status()->attach($status->id, ['user_id'=>1, 'comment'=>__('Automatically approved by admin')]);

                //Notify Owner
                //Find owner
                $restorant->user->notify((new OrderNotification($order))->locale(strtolower(config('settings.app_locale'))));
            }
        }

        //Clear cart
        if (! config('settings.is_whatsapp_ordering_mode') || ! config('settings.whatsapp_ordering_enabled') || ! config('settings.enable_facebook_ordering') || ! config('settings.is_facebook_ordering_mode')) {
            Cart::clear();
        }

        /*if($request['pay_methods'] != "payment"){
            Cart::clear();
        }*/

        if (config('app.isqrsaas') && ! config('settings.is_whatsapp_ordering_mode') && ! config('settings.whatsapp_ordering_enabled') && ! config('settings.enable_facebook_ordering') && ! config('settings.is_facebook_ordering_mode')) {
            //QR
            //Now we need somehow to track users orders
            $previousOrders = Cookie::get('orders') ? Cookie::get('orders') : '';
            $previousOrderArray = array_filter(explode(',', $previousOrders));

            if ($isOldLocalOrder) {
                $status = __('Order updated.').' ID #'.$order->id;

                return redirect()->route('vendor', ['alias'=>$restorant->subdomain])->withStatus(__('Order updated.').' ID #'.$order->id);
            } else {
                //New order
                array_push($previousOrderArray, $order->id);
                $status = __('Order created.').' ID #'.$order->id;

                $CAN_USE_PUSHER = strlen(config('broadcasting.connections.pusher.app_id')) > 2 && strlen(config('broadcasting.connections.pusher.key')) > 2 && strlen(config('broadcasting.connections.pusher.secret')) > 2;
                //notify owner with pusher
                if ($CAN_USE_PUSHER) {
                    $msg = __('notications.notification_neworder');

                    event(new NewOrder($order, $msg));
                }
            }

            $listOfOrders = implode(',', $previousOrderArray);

            if ($request->paymentType == 'mollie') {

                //redirect customer to Mollie checkout page
                return redirect($redirect_url, 303);
            } elseif ($request->paymentType == 'paystack') {

                //redirect customer to Paystack checkout page
                return $redirect_url->redirectNow();
            } elseif ($request->paymentType == 'paypal') {

                //redirect customer to PayPal checkout page
                return redirect($redirect_url);
            }

            return redirect()->route('vendor', ['alias'=>$restorant->subdomain])->withCookie(cookie('orders', $listOfOrders, 360))->withStatus(__('Order updated.').' ID #'.$order->id);
        } elseif (config('settings.is_whatsapp_ordering_mode') || config('settings.whatsapp_ordering_enabled')) {
            //WhatsApp order

            $text = $this->generateOrderMsg($request->exists('addressID') ? $request->addressID : null, $request->exists('comment') ? $request->comment : null, $orderPrice);

            $url = 'https://wa.me/'.$restorant->whatsapp_phone.'?text='.$text;

            Cart::clear();

            return Redirect::to($url);
        } elseif (config('settings.enable_facebook_ordering') || config('settings.is_facebook_ordering_mode')) {
            //Facebook order

            $url = 'https://m.me/'.$restorant->fb_username;

            Cart::clear();

            return Redirect::to($url);
        } else {
            //FT
            if ($request->paymentType == 'mollie') {

                //redirect customer to Mollie checkout page
                return redirect($redirect_url, 303);
            } elseif ($request->paymentType == 'paystack') {

                //redirect customer to Paystack checkout page
                return $redirect_url->redirectNow();
            } elseif ($request->paymentType == 'paypal') {

                //redirect customer to PayPal checkout page
                return redirect($redirect_url);
            } else {
                return redirect()->route('order.success', ['order' => $order]);
                //return redirect()->route('orders.index')->withStatus(__('Order created.'));
            }
            // return redirect()->route('orders.index')->withStatus(__('Order created.'));
        }
    }

    public function orderLocationAPI(Order $order)
    {
        if ($order->status->pluck('alias')->last() == 'picked_up') {
            return response()->json(
                [
                    'status'=>'tracing',
                    'lat'=>$order->lat,
                    'lng'=>$order->lng,
                    ]
            );
        } else {
            //return null
            return response()->json(['status'=>'not_tracing']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {

        //Change currency
        ConfChanger::switchCurrency($order->restorant);

        //Change language
        ConfChanger::switchLanguage($order->restorant);

        $drivers = User::role('driver')->get();

        if (auth()->user()->hasRole('client') && auth()->user()->id == $order->client_id ||
            auth()->user()->hasRole('owner') && auth()->user()->id == $order->restorant->user->id ||
                auth()->user()->hasRole('driver') && auth()->user()->id == $order->driver_id || auth()->user()->hasRole('admin')
            ) {
            return view('orders.show', ['order'=>$order, 'statuses'=>Status::pluck('name', 'id'), 'drivers'=>$drivers]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function liveapi()
    {

        //TODO - Method not allowed for client or driver
        if (auth()->user()->hasRole('client')) {
            dd('Not allowed as client');
        }

        //Today only
        $orders = Order::where('created_at', '>=', Carbon::today())->orderBy('created_at', 'desc');

        //If owner, only from his restorant
        if (auth()->user()->hasRole('owner')) {
            $orders = $orders->where(['restorant_id'=>auth()->user()->restorant->id]);
            
            //Change currency
            ConfChanger::switchCurrency(auth()->user()->restorant);

            //Change language
            //ConfChanger::switchLanguage($order->restorant);
        }
        $orders = $orders->with(['status', 'client', 'restorant', 'table.restoarea'])->get()->toArray();

        

        $newOrders = [];
        $acceptedOrders = [];
        $doneOrders = [];

        $items = [];
        foreach ($orders as $key => $order) {
            array_push($items, [
                'id'=>$order['id'],
                'restaurant_name'=>$order['restorant']['name'],
                'last_status'=>count($order['status']) > 0 ? __($order['status'][count($order['status']) - 1]['name']) : 'Just created',
                'last_status_id'=>count($order['status']) > 0 ? $order['status'][count($order['status']) - 1]['pivot']['status_id'] : 1,
                'time'=>$order['updated_at'],
                'client'=>config('app.isft') ? $order['client']['name'] : ($order['table']['restoarea'] ? $order['table']['restoarea']['name'].' - '.$order['table']['name'] : $order['table']['name']),
                'link'=>'/orders/'.$order['id'],
                'price'=>money($order['order_price'], config('settings.cashier_currency'), config('settings.do_convertion')).'',
            ]);
        }

        //dd($items);

        /**

         */

        //----- ADMIN ------
        if (auth()->user()->hasRole('admin')) {
            foreach ($items as $key => $item) {
                //Box 1 - New Orders
                //Today orders that are just created ( Needs approvment or rejection )
                //Box 2 - Accepted
                //Today orders approved by Restaurant , or by admin( Needs assign to driver )
                //Box 3 - Done
                //Today orders assigned with driver, or rejected
                if ($item['last_status_id'] == 1) {
                    $item['pulse'] = 'blob green';
                    array_push($newOrders, $item);
                } elseif ($item['last_status_id'] == 2 || $item['last_status_id'] == 3) {
                    $item['pulse'] = 'blob orangestatic';
                    if ($item['last_status_id'] == 3) {
                        $item['pulse'] = 'blob orange';
                    }
                    array_push($acceptedOrders, $item);
                } elseif ($item['last_status_id'] > 3) {
                    $item['pulse'] = 'blob greenstatic';
                    if ($item['last_status_id'] == 9 || $item['last_status_id'] == 8) {
                        $item['pulse'] = 'blob redstatic';
                    }
                    array_push($doneOrders, $item);
                }
            }
        }

        //----- Restaurant ------
        if (auth()->user()->hasRole('owner')) {
            foreach ($items as $key => $item) {

                
                //Box 1 - New Orders
                //Today orders that are approved by admin ( Needs approvment or rejection )
                //Box 2 - Accepted
                //Today orders approved by Restaurant ( Needs change of status to done )
                //Box 3 - Done
                //Today completed or rejected
                $last_status = $item['last_status_id'];
                if ($last_status == 2 || $last_status == 10 || ($item['last_status_id'] == 1 && config('app.isqrsaas'))) {
                    $item['pulse'] = 'blob green';
                    array_push($newOrders, $item);
                } elseif ($last_status == 3 || $last_status == 4 || $last_status == 5) {
                    $item['pulse'] = 'blob orangestatic';
                    if ($last_status == 3) {
                        $item['pulse'] = 'blob orange';
                    }
                    array_push($acceptedOrders, $item);
                } elseif ($last_status > 5 && $last_status != 8) {
                    $item['pulse'] = 'blob greenstatic';
                    if ($last_status == 9 || $last_status == 8) {
                        $item['pulse'] = 'blob redstatic';
                    }
                    array_push($doneOrders, $item);
                }
            }
        }

        $toRespond = [
                'neworders'=>$newOrders,
                'accepted'=>$acceptedOrders,
                'done'=>$doneOrders,
            ];

        return response()->json($toRespond);
    }

    public function live()
    {
        return view('orders.live');
    }

    public function autoAssignToDriver(Order $order)
    {

        //The restaurant id
        $restaurant_id = $order->restorant_id;

        //1. Get all the working drivers, where active and working
        $theQuery = User::role('driver')->where(['active'=>1, 'working'=>1]);

        //2. Get Drivers with their assigned order, where payment_status is unpaid yet, this order is still not delivered and not more than 1
        $theQuery = $theQuery->whereHas('driverorders', function (Builder $query) {
            $query->where('payment_status', '!=', 'paid')->where('created_at', '>=', Carbon::today());
        }, '<=', 1);

        //Get Restaurant lat / lng
        $restaurant = Restorant::findOrFail($restaurant_id);
        $lat = $restaurant->lat;
        $lng = $restaurant->lng;

        //3. Sort drivers by distance from the restaurant
        $driversWithGeoIDS = $this->scopeIsWithinMaxDistance($theQuery, $lat, $lng, config('settings.driver_search_radius'), 'users')->pluck('id')->toArray();

        //4. The top driver gets the order
        if (count($driversWithGeoIDS) == 0) {
            //No driver found -- this will appear in  the admin list also in the list of free order so driver can get an order
            //dd('no driver found');
        } else {
            //Driver found
            ///dd('driver found: '.$driversWithGeoIDS[0]);
            $order->driver_id = $driversWithGeoIDS[0];
            $order->update();
            $order->status()->attach([4 => ['comment'=>'System', 'user_id' => $driversWithGeoIDS[0]]]);

            //Now increment the driver orders
            $theDriver = User::findOrFail($order->driver_id);
            $theDriver->numorders = $theDriver->numorders + 1;
            $theDriver->update();
        }
    }

    public function updateStatus($alias, Order $order)
    {
        if (isset($_GET['driver'])) {
            $order->driver_id = $_GET['driver'];
            $order->update();

            //Now increment the driver orders
            $theDriver = User::findOrFail($order->driver_id);
            $theDriver->numorders = $theDriver->numorders + 1;
            $theDriver->update();
        }

        if (isset($_GET['time_to_prepare'])) {
            $order->time_to_prepare = $_GET['time_to_prepare'];
            $order->update();
        }

        $status_id_to_attach = Status::where('alias', $alias)->value('id');

        //Check access before updating
        /**
         * 1 - Super Admin
         * accepted_by_admin
         * assigned_to_driver
         * rejected_by_admin.
         *
         * 2 - Restaurant
         * accepted_by_restaurant - 3
         * prepared
         * rejected_by_restaurant
         * picked_up
         * delivered
         *
         * 3 - Driver
         * picked_up
         * delivered
         */
        //

        $rolesNeeded = [
            'accepted_by_admin'=>'admin',
            'assigned_to_driver'=>'admin',
            'rejected_by_admin'=>'admin',
            'accepted_by_restaurant'=>'owner',
            'prepared'=>'owner',
            'rejected_by_restaurant'=>'owner',
            'picked_up'=>['driver', 'owner'],
            'delivered'=>['driver', 'owner'],
            'closed'=>'owner',
        ];

        if (! auth()->user()->hasRole($rolesNeeded[$alias])) {
            abort(403, 'Unauthorized action. You do not have the appropriate role');
        }

        //For owner - make sure this is his order
        if (auth()->user()->hasRole('owner')) {
            //This user is owner, but we must check if this is order from his restaurant
            if (auth()->user()->id != $order->restorant->user_id) {
                abort(403, 'Unauthorized action. You are not owner of this order restaurant');
            }
        }

        //For driver - make sure he is assigned to this order
        if (auth()->user()->hasRole('driver')) {
            //This user is owner, but we must check if this is order from his restaurant
            if (auth()->user()->id != $order->driver->id) {
                abort(403, 'Unauthorized action. You are not driver of this order');
            }
        }

        /**
         * IF status
         * Accept  - 3
         * Prepared  - 5
         * Rejected - 9.
         */
        // dd($status_id_to_attach."");

        if (config('app.isft')) {
            if ($status_id_to_attach.'' == '3' || $status_id_to_attach.'' == '5' || $status_id_to_attach.'' == '9') {
                $order->client->notify(new OrderNotification($order, $status_id_to_attach));
            }

            if ($status_id_to_attach.'' == '4') {
                $order->driver->notify(new OrderNotification($order, $status_id_to_attach));
            }
        }

        //Picked up - start tracing
        if ($status_id_to_attach.'' == '6') {
            $order->lat = $order->restorant->lat;
            $order->lng = $order->restorant->lng;
            $order->update();
        }

        if (config('app.isft') && $alias.'' == 'delivered') {
            $order->payment_status = 'paid';
            $order->update();
        }

        if (config('app.isqrsaas') && $alias.'' == 'closed') {
            $order->payment_status = 'paid';
            $order->update();
        }

        if (config('app.isft')) {
            //When orders is accepted by restaurant, auto assign to driver
            if ($status_id_to_attach.'' == '3') {
                if (config('settings.allow_automated_assign_to_driver')) {
                    $this->autoAssignToDriver($order);
                }
            }
        }

        //$order->status()->attach([$status->id => ['comment'=>"",'user_id' => auth()->user()->id]]);
        $order->status()->attach([$status_id_to_attach => ['comment'=>'', 'user_id' => auth()->user()->id]]);

        return redirect()->route('orders.index')->withStatus(__('Order status succesfully changed.'));
    }

    public function rateOrder(Request $request, Order $order)
    {
        $restorant = $order->restorant;

        $rating = new Rating;
        $rating->rating = $request->ratingValue;
        $rating->user_id = auth()->user()->id;
        $rating->order_id = $order->id;
        $rating->comment = $request->comment;

        $restorant->ratings()->save($rating);

        return redirect()->route('orders.show', ['order'=>$order])->withStatus(__('Order succesfully rated!'));
    }

    public function checkOrderRating(Order $order)
    {
        $rating = DB::table('ratings')->select('rating')->where(['order_id' => $order->id])->get()->first();
        $is_rated = false;

        if (! empty($rating)) {
            $is_rated = true;
        }

        return response()->json(
            [
                'rating' => $rating->rating,
                'is_rated' => $is_rated,
                ]
        );
    }

    public function guestOrders()
    {
        $previousOrders = Cookie::get('orders') ? Cookie::get('orders') : '';
        $previousOrderArray = array_filter(explode(',', $previousOrders));

        //Find the orders
        $orders = Order::whereIn('id', $previousOrderArray)->orderBy('id', 'desc')->get();
        $backUrl = url()->previous();
        foreach ($orders as $key => $order) {
            $backUrl = route('vendor', $order->restorant->subdomain);
        }

        return view('orders.guestorders', ['backUrl'=>$backUrl, 'orders'=>$orders, 'statuses'=>Status::pluck('name', 'id')]);
    }


    public function generateOrderMsg($address, $comment, $price)
    {
        $title = 'New order #'.strtoupper(Str::random(5))."\n\n";

        $price = '*Price*: '.$price.' '.config('settings.cashier_currency')."\n\n";

        $items = '*Order:*'."\n";
        foreach (Cart::getContent() as $key => $item) {
            $items .= strval($item->quantity).' x '.$item->name."\n";
        }
        $items .= "\n";
        $final = $title.$price.$items;

        if ($address != null) {
            $final .= '*Address*:'."\n".$address."\n\n";
        }

        if ($comment != null) {
            $final .= '*Comment:*'."\n".$comment."\n\n";
        }

        return urlencode($final);
    }

    public function fbOrderMsg(Request $request)
    {
        $orderPrice = Cart::getSubTotal();

        $title = 'New order #'.strtoupper(Str::random(5))."\n\n";

        $price = '*Price*: '.$orderPrice.' '.config('settings.cashier_currency')."\n\n";

        $items = '*Order:*'."\n";
        foreach (Cart::getContent() as $key => $item) {
            $items .= strval($item->quantity).' x '.$item->name."\n";
        }
        $items .= "\n";
        $final = $title.$price.$items;

        if ($request->address != null) {
            $final .= '*Address*:'."\n".$request->address."\n\n";
        }

        if ($request->comment != null) {
            $final .= '*Comment:*'."\n".$request->comment."\n\n";
        }

        return response()->json(
            [
                'status' => true,
                'msg' => $final,
            ]
        );
    }

    public function storeWhatsappOrder(Request $request)
    {
        $restorant_id = null;
        foreach (Cart::getContent() as $key => $item) {
            $restorant_id = $item->attributes->restorant_id;
        }

        $restorant = Restorant::findOrFail($restorant_id);

        $orderPrice = Cart::getSubTotal();

        if ($request->exists('deliveryType')) {
            $isDelivery = $request->deliveryType == 'delivery';
        }

        $text = $this->generateWhatsappOrder($request->exists('addressID') ? $request->addressID : null, $request->exists('comment') ? $request->comment : null, $orderPrice);

        $url = 'https://wa.me/'.$restorant->whatsapp_phone.'?text='.$text;

        Cart::clear();

        return Redirect::to($url);
    }

    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order);
        return view('orders.success', ['order' => $order]);
    }
}

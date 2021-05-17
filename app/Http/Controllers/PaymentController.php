<?php

namespace App\Http\Controllers;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use App\Items;
use App\Notifications\OrderNotification;
use App\Order;
use App\Plans;
use App\Restorant;
use App\Status;
use App\User;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
//use PayPal\Auth\OAuthTokenCredential;
//use PayPal\Rest\ApiContext;

use Mollie\Laravel\Facades\Mollie;
//use App\Payment;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Paystack;

class PaymentController extends Controller
{
    private $apiContext;

    public function __construct()
    {
        // $this->middleware('auth');
       /*$paypalConfig = config('paypal');
       $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $paypalConfig['client_id'],
            $paypalConfig['secret'])
       );

       $this->apiContext->setConfig($paypalConfig['settings']);*/
    }

    public function view(Request $request)
    {
        $total = Money(Cart::getSubTotal(), config('settings.cashier_currency'), config('settings.do_convertion'))->format();

        //Clear cart
        Cart::clear();

        return view('payment.payment', [
            'total' => $total,
        ]);
    }

    public function payment(Request $request)
    {
        try {
            $payment_stripe = auth()->user()->charge(100, $request->payment_method);

            $name = $payment_stripe->charges->data[0]->billing_details->name;
            $country = $payment_stripe->charges->data[0]->payment_method_details->card->country;

            $payment = new Payment;
            $payment->user_id = auth()->user()->id;
            $payment->name = $name != null ? $name : '';
            $payment->stripe_id = $payment_stripe->customer != null ? $payment_stripe->customer : null;
            $payment->amount = $payment_stripe->amount != null ? $payment_stripe->amount : 0.0;
            $payment->currency = $payment_stripe->currency != null ? $payment_stripe->currency : '';
            $payment->country = $country != null ? $country : '';
            $payment->provider = 'stripe';

            $payment->save();

            return response()->json([
                'status' => true,
                'success_url' => redirect()->intended('/')->getTargetUrl(),
                'msg' => 'Payment submitted succesfully!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    //Paypal callback after payment
    public function executePaymentPayPal(Request $request)
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config('services.paypal.client_id'),     // ClientID
                config('services.paypal.secret')     // ClientID
            )
        );

        try {
            $dataArray = $request->all();

            /* If the request success is true process with payment execution*/
            if (isset($_GET['success']) && $_GET['success'] == 'true' && $dataArray['success'] == 'true') {

                /* If the payer ID or token aren't set, there was a corrupt response */
                if (empty($dataArray['PayerID']) || empty($dataArray['token'])) {
                    return redirect()->route('cart.checkout')->withMesswithErrorage('The payment attempt failed because additional action is required before it can be completed.')->withInput();
                }

                $paymentId = $_GET['paymentId'];
                $payment = Payment::get($paymentId, $apiContext);

                $execution = new PaymentExecution();
                $execution->setPayerId($_GET['PayerID']);

                $result = $payment->execute($execution, $apiContext);

                if ($result->getState() == 'approved') {
                    $order_id = intval($result->transactions[0]->invoice_number);

                    $order = Order::findOrFail($order_id);
                    //$restorant = Restorant::findOrFail($order->restorant->id);

                    $order->payment_status = 'paid';

                    $order->update();

                    //return redirect()->route('orders.index')->withStatus(__('Order created.'));
                    return redirect()->route('order.success', ['order' => $order]);
                }
            }
        } catch (Exception $ex) {
            //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex)
            return redirect()->route('cart.checkout')->withMesswithErrorage('The payment attempt failed because additional action is required before it can be completed.')->withInput();
            //exit(1);
        }
    }

    /**
     * Redirect the User to Paystack Payment Page.
     * @return Url
     */
    public function redirectToGateway()
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }
    }

    //Paystack callback after payment
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();

        //regular payment
        if ($paymentDetails['status'] && ! $paymentDetails['data']['plan_object']) {
            $order = Order::findOrFail($paymentDetails['data']['metadata']['order_id']);
            $order->payment_status = 'paid';

            $order->update();

            //return redirect()->route('orders.index')->withStatus(__('Order created.'));
            return redirect()->route('order.success', ['order' => $order]);
        }
        //subscribtion
        elseif ($paymentDetails['status'] && $paymentDetails['data']['plan_object']) {
            $plan_id = $paymentDetails['data']['metadata']['plan_id'];
            $transaction_id = $paymentDetails['data']['id'];

            //Assign user to plan
            auth()->user()->plan_id = $plan_id;
            auth()->user()->paystack_trans_id = $transaction_id;
            auth()->user()->update();

            return redirect()->route('plans.current')->withStatus(__('Plan update!'));
        } else {
            return redirect()->route('cart.checkout')->withError('The payment attempt failed.')->withInput();
        }
    }

    //Mollie webhook after payment
    public function handleWebhookNotification(Request $request)
    {
        if (! $request->has('id')) {
            return;
        }

        $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get($paymentId);

        if ($payment->isPaid()) {
            $order = Order::findOrFail($payment->metadata->order_id);
            $order->payment_status = 'paid';

            $order->update();
        }
    }
}

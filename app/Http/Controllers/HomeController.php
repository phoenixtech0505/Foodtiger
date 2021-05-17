<?php

namespace App\Http\Controllers;

use App\Items;
use App\Order;
use App\Restorant;
use App\User;
use Carbon\Carbon;
use DB;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (! config('app.ordering')) {
            if (auth()->user()->hasRole('owner')) {
                return redirect()->route('admin.restaurants.edit', auth()->user()->restorant->id);
            } elseif (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.restaurants.index');
            }
        }
        $months = [
            0 => __('Jan'),
            1 => __('Feb'),
            2 => __('Mar'),
            3 => __('Apr'),
            4 => __('May'),
            5 => __('Jun'),
            6 => __('Jul'),
            7 => __('Aug'),
            8 => __('Sep'),
            9 => __('Oct'),
            10 => __('Nov'),
            11 => __('Dec'),
        ];

        if (auth()->user()->hasRole('admin')) {
            //first analytics
            $last30days = Carbon::now()->subDays(30);
            $last30daysOrders = Order::all()->where('created_at', '>', $last30days)->count();
            $last30daysOrdersValue = Order::all()->where('created_at', '>', $last30days)->sum('order_price');
            //$uniqueUsersOrders = Order::all()->unique('address_id')->count();
            $uniqueUsersOrders = Order::select('client_id')->groupBy('client_id')->get()->count();
            $allClients = User::all()->count();

            //Last 7 months sales values
            $sevenMonthsDate = Carbon::now()->subMonths(6)->startOfMonth();
            $salesValue = DB::table('orders')
                        ->select(DB::raw('SUM(order_price + delivery_price) AS sumValue'))
                        ->where('created_at', '>', $sevenMonthsDate)
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('sumValue');

            $monthLabels = DB::table('orders')
                        ->select(DB::raw('MONTH(created_at) as month'))
                        ->where('created_at', '>', $sevenMonthsDate)
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('month');

            $totalOrders = DB::table('orders')
                        ->select(DB::raw('count(id) as totalPerMonth'))
                        ->where('created_at', '>', $sevenMonthsDate)
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('totalPerMonth');

            $last30daysDeliveryFee = Order::all()->where('created_at', '>', $last30days)->sum('delivery_price');
            $last30daysStaticFee = Order::all()->where('created_at', '>', $last30days)->sum('static_fee');
            $last30daysDynamicFee = Order::all()->where('created_at', '>', $last30days)->sum('fee_value');
            $last30daysTotalFee = DB::table('orders')
                                ->select(DB::raw('SUM(delivery_price + static_fee + fee_value) AS sumValue'))
                                ->where('created_at', '>', $last30days)
                                ->value('sumValue');

            //dd(Carbon::now()->format('M'));
            $views = Restorant::sum('views');

            return view('dashboard', [
                'last30daysOrders' => $last30daysOrders,
                'last30daysOrdersValue'=> $last30daysOrdersValue,
                'uniqueUsersOrders' => $uniqueUsersOrders,
                'allClients' => $allClients,
                'allViews' => $views,
                'salesValue' => $salesValue,
                'monthLabels' => $monthLabels,
                'totalOrders' => $totalOrders,
                'countItems'=>Restorant::count(),
                'last30daysDeliveryFee' => $last30daysDeliveryFee,
                'last30daysStaticFee' => $last30daysStaticFee,
                'last30daysDynamicFee' => $last30daysDynamicFee,
                'last30daysTotalFee' => $last30daysTotalFee,
                'months' => $months,
            ]);
        } elseif (auth()->user()->hasRole('owner')) {
            //first analytics
            $restorant_id = auth()->user()->restorant->id;

            //Change currency
            \App\Services\ConfChanger::switchCurrency(auth()->user()->restorant);

            $last30days = Carbon::now()->subDays(30);
            // $last30daysOrders = Order::all()->where('created_at', '>', $last30days, 'AND', 'restorant_id', '=' ,$restorant_id)->count();
            $last30daysOrders = Order::where([
                ['created_at', '>', $last30days],
                ['restorant_id', '=', $restorant_id],
            ])->count();

            //$last30daysOrdersValue = Order::all()->where('created_at', '>', $last30days, 'AND', 'restorant_id', '=', $restorant_id)->sum('order_price');
            $last30daysOrdersValue = Order::where([
                ['created_at', '>', $last30days],
                ['restorant_id', '=', $restorant_id],
                ['payment_status', '=', 'paid'],
            ])->sum('order_price');

            //$uniqueUsersOrders = Order::all()->unique('address_id')->where('restorant_id', '=', $restorant_id)->count();
            $uniqueUsersOrders = Order::select('client_id')->where('restorant_id', '=', $restorant_id)->groupBy('client_id')->get()->count();

            //update this query when will be added user id column in the orders
            $allClients = User::all()->count();

            //Last 7 months sales values
            $sevenMonthsDate = Carbon::now()->subMonths(6)->startOfMonth();
            /*$salesValue = DB::table('orders')
                        ->select(DB::raw('SUM(order_price + delivery_price) AS sumValue'))
                        ->where('created_at', '>', $sevenMonthsDate, 'AND', 'restorant_id', '=', $restorant_id)
                        ->groupBy(DB::raw("YEAR(created_at), MONTH(created_at)"))
                        ->orderBy(DB::raw("YEAR(created_at), MONTH(created_at)"), 'asc')
                        ->pluck('sumValue');*/
            $salesValue = DB::table('orders')
                        ->select(DB::raw('SUM(order_price + delivery_price) AS sumValue'))
                        ->where([['created_at', '>', $sevenMonthsDate], ['restorant_id', '=', $restorant_id], ['payment_status', '=', 'paid']])
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('sumValue');

            /*$monthLabels = DB::table('orders')
                        ->select(DB::raw('MONTH(created_at) as month'))
                        ->where('created_at', '>', $sevenMonthsDate, 'AND', 'restorant_id', '=', $restorant_id)
                        ->groupBy(DB::raw("YEAR(created_at), MONTH(created_at)"))
                        ->orderBy(DB::raw("YEAR(created_at), MONTH(created_at)"), 'asc')
                        ->pluck('month');*/
            $monthLabels = DB::table('orders')
                        ->select(DB::raw('MONTH(created_at) as month'))
                        ->where([['created_at', '>', $sevenMonthsDate], ['restorant_id', '=', $restorant_id]])
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('month');

            /*$totalOrders = DB::table('orders')
                        ->select(DB::raw('count(id) as totalPerMonth'))
                        ->where('created_at', '>', $sevenMonthsDate, 'AND', 'restorant_id', '=', $restorant_id)
                        ->groupBy(DB::raw("YEAR(created_at), MONTH(created_at)"))
                        ->orderBy(DB::raw("YEAR(created_at), MONTH(created_at)"), 'asc')
                        ->pluck('totalPerMonth');*/
            $totalOrders = DB::table('orders')
                        ->select(DB::raw('count(id) as totalPerMonth'))
                        ->where([['created_at', '>', $sevenMonthsDate], ['restorant_id', '=', $restorant_id]])
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->orderBy(DB::raw('YEAR(created_at), MONTH(created_at)'), 'asc')
                        ->pluck('totalPerMonth');

            $last30daysDeliveryFee = Order::where([['created_at', '>', $last30days], ['restorant_id', '=', $restorant_id]])->sum('delivery_price');
            $last30daysStaticFee = Order::where([['created_at', '>', $last30days], ['restorant_id', '=', $restorant_id]])->sum('static_fee');
            $last30daysDynamicFee = Order::where([['created_at', '>', $last30days], ['restorant_id', '=', $restorant_id]])->sum('fee_value');
            $last30daysTotalFee = DB::table('orders')
                                ->select(DB::raw('SUM(delivery_price + static_fee + fee_value) AS sumValue'))
                                ->where([['created_at', '>', $last30days], ['restorant_id', '=', $restorant_id]])
                                ->value('sumValue');
            $itemsCount = Items::whereIn('category_id', auth()->user()->restorant->categories->pluck('id')->toArray())->count();

            return view('dashboard', [
                'last30daysOrders' => $last30daysOrders,
                'last30daysOrdersValue'=> $last30daysOrdersValue,
                'uniqueUsersOrders' => $uniqueUsersOrders,
                'allClients' => $allClients,
                'allViews' => auth()->user()->restorant->views,
                'salesValue' => $salesValue,
                'monthLabels' => $monthLabels,
                'totalOrders' => $totalOrders,
                'countItems'=>$itemsCount,
                'last30daysDeliveryFee' => $last30daysDeliveryFee,
                'last30daysStaticFee' => $last30daysStaticFee,
                'last30daysDynamicFee' => $last30daysDynamicFee,
                'last30daysTotalFee' => $last30daysTotalFee,
                'months' => $months,
            ]);
        } elseif (auth()->user()->hasRole('driver')) {
            return redirect()->route('orders.index');
        } elseif (auth()->user()->hasRole('client')) {
            return redirect()->route('front');
        }
    }
}

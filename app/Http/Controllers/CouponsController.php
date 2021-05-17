<?php

namespace App\Http\Controllers;

use App\Coupons;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponsController extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Coupons::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'admin.restaurant.coupons.';

    /**
     * View path.
     */
    private $view_path = 'coupons.';

    /**
     * Parameter name.
     */
    private $parameter_name = 'coupon';

    /**
     * Title of this crud.
     */
    private $title = 'coupon';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'coupons';

    private function getFields()
    {
        return [
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'Enter code name', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input', 'type'=>'number', 'name'=>'Code', 'id'=>'size', 'placeholder'=>'Enter table person size, ex 4', 'required'=>true],
            ['ftype'=>'select', 'name'=>'Price', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Active from', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Active to', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Limit number', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
            ['ftype'=>'select', 'name'=>'Used from', 'id'=>'type', 'placeholder'=>'Select type', 'data'=>['Fixed', 'Percentage'], 'required'=>true],
        ];
    }

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker()
    {
        $this->ownerOnly();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authChecker();

        return view($this->view_path.'index', ['setup' => [
            'title'=>__('crud.item_managment', ['item'=>__($this->titlePlural)]),
            'action_link'=>route($this->webroute_path.'create'),
            'action_name'=>__('crud.add_new_item', ['item'=>__($this->title)]),
            'items'=>$this->getRestaurant()->coupons()->paginate(config('settings.paginate')),
            'item_names'=>$this->titlePlural,
            'webroute_path'=>$this->webroute_path,
            'fields'=>$this->getFields(),
            'parameter_name'=>$this->parameter_name,
        ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authChecker();

        return view('coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authChecker();
        $item = $this->provider::create([
            'name' => $request->name,
            'code' => strtoupper(substr($this->getRestaurant()->name, 0, 2).(Str::random(6))),
            'type' => $request->type,
            'price' => $request->type == 0 ? $request->price_fixed : $request->price_percentage,
            'active_from' => $request->active_from,
            'active_to' => $request->active_to,
            'limit_to_num_uses' => $request->limit_to_num_uses,
            'restaurant_id' => $this->getRestaurant()->id,
        ]);

        $item->save();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function show(Coupons $coupons)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupons $coupon)
    {
        return view('coupons.create', ['coupon' => $coupon]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $item->name = $request->name;
        $item->type = $request->type;
        $item->price = $request->type == 0 ? $request->price_fixed : $request->price_percentage;
        $item->active_from = $request->active_from;
        $item->active_to = $request->active_to;
        $item->limit_to_num_uses = $request->limit_to_num_uses;

        $item->update();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupons  $coupons
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $item->delete();

        //TODO -- Delete customers from this table
        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_removed', ['item'=>__($this->title)]));
    }

    public function apply(Request $request)
    {
        //'data' => Cart::getContent(),
        $coupon = Coupons::where(['code' => $request->code])->get()->first();

        $dateActive = false;

        if ((new Carbon($coupon->active_to))->gt(new Carbon($coupon->active_from)) && Carbon::now()->between(new Carbon($coupon->active_from), new Carbon($coupon->active_to))) {
            $dateActive = true;
        } elseif ((new Carbon($coupon->active_from))->eq(new Carbon($coupon->active_to)) && (new Carbon(Carbon::now()->toDateString()))->eq(new Carbon($coupon->active_from)) && (new Carbon(Carbon::now()->toDateString()))->eq(new Carbon($coupon->active_to))) {
            $dateActive = true;
        }

        if ($coupon->type == 0 && $coupon->limit_to_num_uses > 0 && $dateActive) {
            $total = Cart::getSubTotal() - $coupon->price;
            $coupon->decrement('limit_to_num_uses');
            $coupon->increment('used_count');

            return response()->json([
                'total' => $total,
                'status' => true,
                'msg' => __('Coupon code applied successfully.'),
            ]);
        } elseif ($coupon->type == 1 && $coupon->limit_to_num_uses > 0 && $dateActive) {
            $myNumber = Cart::getSubTotal();

            //I want to get 25% of 928.
            $percentToGet = $coupon->price;

            //Convert our percentage value into a decimal.
            $percentInDecimal = $percentToGet / 100;

            //Get the result.
            $percent = $percentInDecimal * $myNumber;

            $total = number_format((float) Cart::getSubTotal() - $percent, 2, '.', '');

            $coupon->decrement('limit_to_num_uses');
            $coupon->increment('used_count');

            return response()->json([
                'total' => $total,
                'status' => true,
                'msg' => __('Coupon code applied successfully.'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => __('The coupon promotion code has been expired or the limit is exceeded.'),
            ]);
        }
    }
}

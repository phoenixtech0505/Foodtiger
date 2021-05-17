<?php

namespace App\Http\Controllers;

use App\Exports\VisitsExport;
use App\Restorant;
use App\Tables;
use App\Visit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VisitsController extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Visit::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'admin.restaurant.visits.';

    /**
     * View path.
     */
    private $view_path = 'visits.';

    /**
     * Parameter name.
     */
    private $parameter_name = 'visit';

    /**
     * Title of this crud.
     */
    private $title = 'customer visit';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'customers visits';

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker()
    {
        $this->ownerOnly();
    }

    /**
     * List of fields for edit and create.
     */
    private function getFields($class = 'col-md-6', $restaurant = null)
    {
        if ($restaurant == null) {
            $restaurant = $this->getRestaurant();
        }

        $tables = Tables::where('restaurant_id', $restaurant->id)->get();
        $tablesData = [];
        foreach ($tables as $key => $table) {
            $tablesData[$table->id] = $table->restoarea ? $table->restoarea->name.' - '.$table->name : $table->name;
        }

        return [
            ['class'=>$class, 'ftype'=>'select', 'name'=>'Table', 'id'=>'table_id', 'placeholder'=>'Select table', 'data'=>$tablesData, 'required'=>true],
            ['class'=>$class, 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>__('Customer name'), 'required'=>true],
            ['class'=>$class, 'ftype'=>'input', 'name'=>'Email', 'id'=>'email', 'placeholder'=>__('Customer email'), 'required'=>false],
            ['class'=>$class, 'ftype'=>'input', 'name'=>'Phone', 'id'=>'phone_number', 'placeholder'=>__('Customer phone'), 'required'=>false],
            ['class'=>$class, 'ftype'=>'input', 'name'=>'Note', 'id'=>'note', 'placeholder'=>__('Custom note'), 'required'=>false],
            ['class'=>$class, 'type'=>'hidden', 'ftype'=>'input', 'name'=>'Restaurant', 'id'=>'restaurant_id', 'placeholder'=>__('Restaurant'), 'required'=>true, 'value'=>$restaurant->id],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authChecker();

        $class = 'col-md-4';
        $fields = $this->getFields($class);
        $fields[1]['required'] = false;
        unset($fields[5]);
        array_push($fields, ['class'=>$class, 'ftype'=>'select', 'name'=>'customers.created_by', 'id'=>'by', 'placeholder'=>'Select who created it', 'data'=>['2'=>__('customers.him_self'), '1'=>__('customers.by_restaurant')], 'required'=>false]);
        array_push($fields, ['class'=>$class, 'editclass'=>' daterange ', 'ftype'=>'input', 'name'=>'customers.visit_time', 'id'=>'created_at', 'placeholder'=>'Created time', 'required'=>false]);

        $items = $this->provider::where('restaurant_id', $this->getRestaurant()->id);

        //Filters
        if (\Request::exists('table_id') && \Request::input('table_id').'' != '') {
            $items = $items->where('table_id', \Request::input('table_id'));
        }
        if (\Request::exists('by') && \Request::input('by').'' != '') {
            $items = $items->where('by', \Request::input('by'));
        }
        if (\Request::exists('name') && \Request::input('name').'' != '') {
            $items = $items->where('name', 'like', '%'.\Request::input('name').'%');
        }
        if (\Request::exists('email') && \Request::input('email').'' != '') {
            $items = $items->where('email', 'like', '%'.\Request::input('email').'%');
        }
        if (\Request::exists('phone_number') && \Request::input('phone_number').'' != '') {
            $items = $items->where('phone_number', 'like', '%'.\Request::input('phone_number').'%');
        }
        if (\Request::exists('note') && \Request::input('note').'' != '') {
            $items = $items->where('note', 'like', '%'.\Request::input('note').'%');
        }
        if (\Request::exists('created_at') && \Request::input('created_at').'' != '') {
            $dates = explode(' - ', \Request::input('created_at'));
            $from = (Carbon::createFromFormat('d/m/Y', $dates[0]))->toDateString();
            $to = (Carbon::createFromFormat('d/m/Y', $dates[1]))->toDateString();

            //Apply dated
            $items->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        }

        //Sorting
        $items = $items->orderBy('id', 'desc');

        //With downloaod
        if (isset($_GET['report'])) {
            $itemsForExport = [];
            foreach ($items->get() as $key => $item) {
                $item = [
                    'visit_id'=>$item->id,
                    'table'=>$item->table->name,
                    'area'=>$item->table->restoarea ? $item->table->restoarea->name : '',
                    'created'=>$item->created_at,
                    'customer_name'=>$item->name,
                    'customer_email'=>$item->email,
                    'customer_phone_number'=>$item->phone_number,
                    'note'=>$item->note,
                    'by'=>$item->by.'' == '1' ? __('customers.by_restaurant') : __('customers.him_self'),
                  ];
                array_push($itemsForExport, $item);
            }

            return Excel::download(new VisitsExport($itemsForExport), 'visits_'.time().'.xlsx');
        }

        //Pagiinate
        $items = $items->paginate(config('settings.paginate'));

        return view($this->view_path.'index', ['setup' => [
            'usefilter'=>true,
            'title'=>__('crud.item_managment', ['item'=>__($this->titlePlural)]),
            'action_link'=>route($this->webroute_path.'create'),
            'action_name'=>__('crud.add_new_item', ['item'=>__($this->title)]),
            'items'=>$items,
            'item_names'=>$this->titlePlural,
            'webroute_path'=>$this->webroute_path,
            'fields'=>$fields,
            'parameter_name'=>$this->parameter_name,
            'parameters'=>count($_GET) != 0,
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

        return view('general.form', ['setup' => [
            'inrow'=>true,
            'title'=>__('crud.new_item', ['item'=>__($this->title)]),
            'action_link'=>route($this->webroute_path.'index'),
            'action_name'=>__('crud.back'),
            'iscontent'=>true,
            'action'=>route($this->webroute_path.'store'),
        ],
        'fields'=>$this->getFields(), ]);
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
            'name'=>$request->name,
            'restaurant_id'=>$this->getRestaurant()->id,
            'table_id'=>$request->table_id,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'note'=>$request->note,
        ]);
        $item->save();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RestoArea  $restoArea
     * @return \Illuminate\Http\Response
     */
    public function show(RestoArea $restoArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $fields = $this->getFields();
        $fields[0]['value'] = $item->table_id;
        $fields[1]['value'] = $item->name;
        $fields[2]['value'] = $item->email;
        $fields[3]['value'] = $item->phone_number;
        $fields[4]['value'] = $item->note;

        $parameter = [];
        $parameter[$this->parameter_name] = $id;

        return view('general.form', ['setup' => [
            'inrow'=>true,
            'title'=>__('crud.edit_item_name', ['item'=>__($this->title), 'name'=>$item->name]),
            'action_link'=>route($this->webroute_path.'index'),
            'action_name'=>__('crud.back'),
            'iscontent'=>true,
            'isupdate'=>true,
            'action'=>route($this->webroute_path.'update', $parameter),
        ],
        'fields'=>$fields, ]);
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
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $item->name = $request->name;
        $item->table_id = $request->table_id;
        $item->email = $request->email;
        $item->phone_number = $request->phone_number;
        $item->note = $request->note;

        $item->update();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $item->delete();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_removed', ['item'=>__($this->title)]));
    }

    public function register($restaurant_id)
    {
        $restaurant = Restorant::findOrFail($restaurant_id);

        return view('general.form_front', ['setup' => [
            'inrow'=>true,
            'action_link'=>route('vendor', ['alias'=>$restaurant->subdomain]),
            'action_name'=>__('crud.back'),
            'title'=>__('crud.new_item', ['item'=>__($this->title)]),
            'iscontent'=>true,
            'action'=>route('register.visit.store'),
        ],
        'fields'=>$this->getFields('col-md-6', $restaurant), ]);
    }

    public function registerstore(Request $request)
    {
        $restaurant = Restorant::findOrFail($request->restaurant_id);
        $item = $this->provider::create([
            'name'=>$request->name,
            'restaurant_id'=>$request->restaurant_id,
            'table_id'=>$request->table_id,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'note'=>$request->note,
            'by'=>2,
        ]);
        $item->save();

        return redirect()->route('vendor', ['alias'=>$restaurant->subdomain])->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }
}

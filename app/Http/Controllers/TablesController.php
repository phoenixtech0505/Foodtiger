<?php

namespace App\Http\Controllers;

use App\RestoArea;
use App\Tables;
use Illuminate\Http\Request;

class TablesController extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Tables::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'admin.restaurant.tables.';

    /**
     * View path.
     */
    private $view_path = 'tables.';

    /**
     * Parameter name.
     */
    private $parameter_name = 'table';

    /**
     * Title of this crud.
     */
    private $title = 'table';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'tables';

    /**
     * Auth checker functin for the crud.
     */
    private function authChecker()
    {
        $this->ownerOnly();
    }

    private function getFields()
    {
        return [
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'Enter table name or internal id, ex Table 8', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input', 'type'=>'number', 'name'=>'Size', 'id'=>'size', 'placeholder'=>'Enter table person size, ex 4', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Area', 'id'=>'restoarea_id', 'placeholder'=>'Selec rest area id', 'data'=>RestoArea::where('restaurant_id', $this->getRestaurant()->id)->pluck('name', 'id')->toArray(), 'required'=>false],
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

        return view($this->view_path.'index', ['setup' => [
            'title'=>__('crud.item_managment', ['item'=>__($this->titlePlural)]),
            'action_link'=>route($this->webroute_path.'create'),
            'action_name'=>__('crud.add_new_item', ['item'=>__($this->title)]),
            'action_link2'=>route('admin.restaurant.restoareas.index'),
            'action_name2'=>__('Areas'),
            'items'=>$this->getRestaurant()->tables()->paginate(config('settings.paginate')),
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
            'restoarea_id'=>$request->restoarea_id,
            'size'=>$request->size,
            'restaurant_id'=>$this->getRestaurant()->id,
        ]);
        $item->save();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tables  $tables
     * @return \Illuminate\Http\Response
     */
    public function show(Tables $tables)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tables  $tables
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $fields = $this->getFields();
        $fields[0]['value'] = $item->name;
        $fields[1]['value'] = $item->size;
        $fields[2]['value'] = $item->restoarea_id;

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
     * @param  \App\Tables  $tables
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authChecker();
        $item = $this->provider::findOrFail($id);
        $item->name = $request->name;
        $item->restoarea_id = $request->restoarea_id;
        $item->size = $request->size;
        $item->update();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tables  $tables
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
}

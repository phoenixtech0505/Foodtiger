<?php

namespace App\Http\Controllers;

use App\Banners;
use App\Pages;
use App\Restorant;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    /**
     * Provide class.
     */
    private $provider = Banners::class;

    /**
     * Web RoutePath for the name of the routes.
     */
    private $webroute_path = 'admin.restaurant.banners.';

    /**
     * View path.
     */
    private $view_path = 'banners.';

    /**
     * Parameter name.
     */
    private $parameter_name = 'banner';

    /**
     * Title of this crud.
     */
    private $title = 'banner';

    /**
     * Title of this crud in plural.
     */
    private $titlePlural = 'banners';

    protected $imagePath = 'uploads/banners/';

    /**
     * Auth checker functin for the crud.
     */
    private function adminOnly()
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function getFields()
    {
        return [
            ['class'=>'col-md-4', 'ftype'=>'input', 'name'=>'Name', 'id'=>'name', 'placeholder'=>'Enter table name or internal id, ex Table 8', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'input', 'type'=>'number', 'name'=>'Vendor/Page', 'id'=>'size', 'placeholder'=>'Enter table person size, ex 4', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Active from', 'id'=>'restoarea_id', 'placeholder'=>'Selec rest area id', 'data'=>'', 'required'=>true],
            ['class'=>'col-md-4', 'ftype'=>'select', 'name'=>'Active to', 'id'=>'restoarea_id', 'placeholder'=>'Selec rest area id', 'data'=>'', 'required'=>true],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Banners $banners)
    {
        $this->adminOnly();

        return view($this->view_path.'index', ['setup' => [
            'title'=>__('crud.item_managment', ['item'=>__($this->titlePlural)]),
            'action_link'=>route($this->webroute_path.'create'),
            'action_name'=>__('crud.add_new_item', ['item'=>__($this->title)]),
            'items'=> $banners->paginate(config('settings.paginate')),
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
        $this->adminOnly();

        $restaurants = Restorant::where(['active'=>1])->get();
        $restaurantsData = [];
        foreach ($restaurants as $key => $restaurant) {
            $restaurantsData[$restaurant->id] = $restaurant->name;
        }

        $pages = Pages::all();
        $pagesData = [];
        foreach ($pages as $key => $page) {
            $pagesData[$page->id] = $page->title;
        }

        return view('banners.create', ['restaurants' => $restaurantsData, 'pages' => $pagesData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->adminOnly();

        $item = $this->provider::create([
            'name' => $request->name,
            'type' => $request->type,
            'vendor_id' => $request->type == 0 ? $request->vendor_id : null,
            'page_id' => $request->type == 1 ? $request->page_id : null,
            'active_from' => $request->active_from,
            'active_to' => $request->active_to,
        ]);

        if ($request->hasFile('banner_image')) {
            $item->img = $this->saveImageVersions(
                $this->imagePath,
                $request->banner_image,
                [
                    ['name'=>'banner', 'w'=>401, 'h'=>170],
                ]
            );
        }

        $item->save();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_added', ['item'=>__($this->title)]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banners  $banners
     * @return \Illuminate\Http\Response
     */
    public function show(Banners $banners)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banners  $banners
     * @return \Illuminate\Http\Response
     */
    public function edit(Banners $banner)
    {
        $this->adminOnly();

        $restaurants = Restorant::where(['active'=>1])->get();
        $restaurantsData = [];
        foreach ($restaurants as $key => $restaurant) {
            $restaurantsData[$restaurant->id] = $restaurant->name;
        }

        $pages = Pages::all();
        $pagesData = [];
        foreach ($pages as $key => $page) {
            $pagesData[$page->id] = $page->title;
        }

        return view('banners.create', ['banner' => $banner, 'restaurants' => $restaurantsData, 'pages' => $pagesData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banners  $banners
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->adminOnly();

        $item = $this->provider::findOrFail($id);
        $item->name = $request->name;
        $item->type = $request->type;
        $item->vendor_id = $request->type == 0 ? $request->vendor_id : null;
        $item->page_id = $request->type == 1 ? $request->page_id : null;
        $item->active_from = $request->active_from;
        $item->active_to = $request->active_to;

        if ($request->hasFile('banner_image')) {
            $item->img = $this->saveImageVersions(
                $this->imagePath,
                $request->banner_image,
                [
                    ['name'=>'banner', 'w'=>401, 'h'=>170],
                ]
            );
        }

        $item->update();

        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_updated', ['item'=>__($this->title)]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banners  $banners
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->adminOnly();

        $item = $this->provider::findOrFail($id);
        $item->delete();

        //TODO -- Delete customers from this table
        return redirect()->route($this->webroute_path.'index')->withStatus(__('crud.item_has_been_removed', ['item'=>__($this->title)]));
    }
}

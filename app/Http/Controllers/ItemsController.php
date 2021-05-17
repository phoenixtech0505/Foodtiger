<?php

namespace App\Http\Controllers;

use App\Extras;
use App\Imports\ItemsImport;
use App\Items;
use App\Plans;
use App\Restorant;
use Illuminate\Http\Request;
use Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ConfChanger;

class ItemsController extends Controller
{
    private $imagePath = 'uploads/restorants/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('owner')) {
            $canAdd = true;
            if (config('app.isqrsaas')) {
                //In QRsaas with plans, we need to check if they can add new items.
                $currentPlan = Plans::withTrashed()->find(auth()->user()->mplanid());
                if ($currentPlan == null) {
                    return redirect()->route('plans.current')->withStatus(__('Please subscribe to a plan first'));
                }
                $items = Items::whereIn('category_id', auth()->user()->restorant->categories->pluck('id')->toArray());
                if ($currentPlan->limit_items != 0) {
                    $canAdd = $currentPlan->limit_items > $items->count();
                }
            }

            //Change language
            ConfChanger::switchLanguage(auth()->user()->restorant);

            if (isset($_GET['remove_lang']) && auth()->user()->restorant->localmenus()->count() > 1) {
                $localMenuToDelete=auth()->user()->restorant->localmenus()->where('language', $_GET['remove_lang'])->first();
                $isMenuToDeleteIsDefault=$localMenuToDelete->default.""=="1";
                $localMenuToDelete->delete();
                
                $nextLanguageModel = auth()->user()->restorant->localmenus()->first();
                $nextLanguage = $nextLanguageModel->language;
                app()->setLocale($nextLanguage);
                session(['applocale_change' => $nextLanguage]);

                if($isMenuToDeleteIsDefault){
                    $nextLanguageModel->default=1;
                    $nextLanguageModel->update();
                }
            }

            if(isset($_GET['make_default_lang'])){
                $newDefault=auth()->user()->restorant->localmenus()->where('language', $_GET['make_default_lang'])->first();
                $oldDefault=auth()->user()->restorant->localmenus()->where('default', "1")->first();
                
                if($oldDefault&&$oldDefault->language!=$_GET['make_default_lang']){
                    $oldDefault->default=0;
                    $oldDefault->update();
                }
                $newDefault->default=1;
                $newDefault->update();
                
                
                
            }

            $currentEnvLanguage = isset(config('config.env')[2]['fields'][0]['data'][config('app.locale')]) ? config('config.env')[2]['fields'][0]['data'][config('app.locale')] : 'UNKNOWN';


            //Change currency
            ConfChanger::switchCurrency(auth()->user()->restorant);
            $defaultLng=auth()->user()->restorant->localmenus->where('default','1')->first();

            return view('items.index', [
                'canAdd'=>$canAdd,
                'categories' => auth()->user()->restorant->categories->reverse(),
                'restorant_id' => auth()->user()->restorant->id,
                'currentLanguage'=> $currentEnvLanguage,
                'availableLanguages'=>auth()->user()->restorant->localmenus,
                'defaultLanguage'=>$defaultLng?$defaultLng->language:""
                ]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    public function indexAdmin(Restorant $restorant)
    {
        if (auth()->user()->hasRole('admin')) {
            return view('items.index', ['categories' => Restorant::findOrFail($restorant->id)->categories->reverse(), 'restorant_id' => $restorant->id]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Items;
        $item->name = strip_tags($request->item_name);
        $item->description = strip_tags($request->item_description);
        $item->price = strip_tags($request->item_price);
        $item->category_id = strip_tags($request->category_id);
        if ($request->hasFile('item_image')) {
            $item->image = $this->saveImageVersions(
                $this->imagePath,
                $request->item_image,
                [
                    ['name'=>'large', 'w'=>590, 'h'=>400],
                    //['name'=>'thumbnail','w'=>300,'h'=>300],
                    ['name'=>'medium', 'w'=>295, 'h'=>200],
                    ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                ]
            );
        }
        $item->save();

        return redirect()->route('items.index')->withStatus(__('Item successfully updated.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Items $item)
    {
        //if item belongs to owner restorant menu return view
        if (auth()->user()->hasRole('owner') && $item->category->restorant->id == auth()->user()->restorant->id || auth()->user()->hasRole('admin')) {
            return view('items.edit',
            [
                'item' => $item,
                'setup'=>['items'=>$item->variants()->paginate(100)],
                'restorant' => $item->category->restorant,
                'restorant_id' => $item->category->restorant->id, ]);
        } else {
            return redirect()->route('items.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Items $item)
    {
        $item->name = strip_tags($request->item_name);
        $item->description = strip_tags($request->item_description);
        $item->price = strip_tags($request->item_price);
        if (isset($request->vat)) {
            $item->vat = $request->vat;
        }

        $item->available = $request->exists('itemAvailable');
        $item->has_variants = $request->exists('has_variants');

        if ($request->hasFile('item_image')) {
            if ($request->hasFile('item_image')) {
                $item->image = $this->saveImageVersions(
                    $this->imagePath,
                    $request->item_image,
                    [
                        ['name'=>'large', 'w'=>590, 'h'=>400],
                        //['name'=>'thumbnail','w'=>300,'h'=>300],
                        ['name'=>'medium', 'w'=>295, 'h'=>200],
                        ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                    ]
                );
            }
        }

        $item->update();

        return redirect()->route('items.edit', $item)->withStatus(__('Item successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Items $item)
    {
        $item->delete();

        return redirect()->route('items.index')->withStatus(__('Item successfully deleted.'));
    }

    public function import(Request $request)
    {
        $restorant = Restorant::findOrFail($request->res_id);

        Excel::import(new ItemsImport($restorant), request()->file('items_excel'));

        //return redirect()->route('admin.restaurants.index')->withStatus(__('Items successfully imported'));
        return back()->withStatus(__('Items successfully imported'));
    }

    public function change(Items $item, Request $request)
    {
        $item->available = $request->value;
        $item->update();

        return response()->json([
            'data' => [
                'itemAvailable' => $item->available,
            ],
            'status' => true,
            'errMsg' => '',
        ]);
    }

    public function storeExtras(Request $request, Items $item)
    {
        //dd($request->all());
        if ($request->extras_id.'' == '') {
            //New
            $extras = new Extras;
            $extras->name = strip_tags($request->extras_name);
            $extras->price = strip_tags($request->extras_price);
            $extras->item_id = $item->id;

            $extras->save();
        } else {
            //Update
            $extras = Extras::where(['id'=>$request->extras_id])->get()->first();

            $extras->name = strip_tags($request->extras_name);
            $extras->price = strip_tags($request->extras_price);

            $extras->update();
        }

        //For variants
        //Does the item of this extra have item?
        if ($item->has_variants.'' == 1) {
            //In cas we have variants, we  need to check if this variant is for all variants, or only for selected one
            if ($request->exists('variantsSelector')) {
                $extras->extra_for_all_variants = 0;
                //Now sync the connection
                $extras->variants()->sync($request->variantsSelector);
            } else {
                $extras->extra_for_all_variants = 1;
            }
        } else {
            $extras->extra_for_all_variants = 1;
        }
        $extras->update();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully added/modified.'));
    }

    public function editExtras(Request $request, Items $item)
    {
        $extras = Extras::where(['id'=>$request->extras_id])->get()->first();

        $extras->name = strip_tags($request->extras_name_edit);
        $extras->price = strip_tags($request->extras_price_edit);

        $extras->update();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully updated.'));
    }

    public function deleteExtras(Items $item, Extras $extras)
    {
        $extras->delete();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully deleted.'));
    }
}

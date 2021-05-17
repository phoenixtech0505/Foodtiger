<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\Restorant as Restaurant;
use App\Address;

class VendorController extends Controller
{
    public function getCities()
    {
        $cities = City::where('id', '>', 1)->get();

        if ($cities) {
            return response()->json([
                'data' => $cities,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Cities not found!',
            ]);
        }
    }

    public function getVendors($city_id = 'none')
    {
        if ($city_id == 'none') {
            $restaurants = Restaurant::where(['active'=>1])->get();
        } else {
            $restaurants = Restaurant::where(['active'=>1])->where(['city_id'=>$city_id])->get();
        }

        if ($restaurants) {
            return response()->json([
                'data' => $restaurants,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

    public function getVendorItems($id)
    {
        $restorant = Restaurant::where(['id' => $id, 'active' => 1])->with(['categories.items.variants.extras'])->first();
        $items = [];
        if ($restorant) {
            if ($restorant->categories) {
                foreach ($restorant->categories as $key => $category) {
                    $theItemsInCategory = $category->items;
                    $catBox = [];
                    foreach ($theItemsInCategory as $key => $item) {
                        $itemObj = $item->toArray();
                        $itemObj['category_name'] = $category->name;
                        $itemObj['extras'] = $item->extras->toArray();
                        $itemObj['options'] = $item->options->toArray();
                        $itemObj['variants'] = $item->variants->toArray();
                        array_push($catBox, $itemObj);
                    }
                    array_push($items, $catBox);
                }

                return response()->json([
                    'data' => $items,
                    'status' => true,
                    'errMsg' => '',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'errMsg' => 'Vendor categories not found!',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Vendor not found!',
            ]);
        }
    }

    public function getVendorHours($restorantID)
    {
        
        //Create all the time slots
        //The restaurant
        $restaurant = Restaurant::findOrFail($restorantID);
        //dd($restaurant->hours);

        $timeSlots = $restaurant->hours ? $this->getTimieSlots($restaurant->hours->toArray()) : [];

        //Modified time slots for app
        $timeSlotsForApp = [];
        foreach ($timeSlots as $key => $timeSlotsTitle) {
            array_push($timeSlotsForApp, ['id'=>$key, 'title'=>$timeSlotsTitle]);
        }

        //Working hours
        $ourDateOfWeek = date('N') - 1;

        $format = 'G:i';
        if (config('settings.time_format') == 'AM/PM') {
            $format = 'g:i A';
        }

        //dd($ourDateOfWeek);
        //dd($restaurant->hours[$ourDateOfWeek.'_from']);

        $openingTime = date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_from']));
        $closingTime = date($format, strtotime($restaurant->hours[$ourDateOfWeek.'_to']));

        $params = [
            'restorant' => $restaurant,
            'timeSlots' => $timeSlotsForApp,
            'openingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_from'] ? $openingTime : null,
            'closingTime' => $restaurant->hours && $restaurant->hours[$ourDateOfWeek.'_to'] ? $closingTime : null,
         ];

        if ($restaurant) {
            return response()->json([
                'data' => $params,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => 'Restaurant not found!',
            ]);
        }
    }

    public function getDeliveryFee($restaurant_id, $address_id)
    {
        $restaurant = Restaurant::findOrFail($restaurant_id);
        $addresss = Address::findOrFail($address_id);
        $addresses = $this->getAccessibleAddresses($restaurant, [$addresss]);
        //dd($addresses[$address_id]);
        return response()->json([
            'fee' => $addresses[$address_id]->cost_total,
            'inRadius' => $addresses[$address_id]->inRadius,
            'address'=>$addresses[$address_id],
            'status' => true,
            'errMsg' => '',
        ]);
    }
}

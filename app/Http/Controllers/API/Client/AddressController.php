<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Address;
use App\Restorant;
use App\User;

class AddressController extends Controller
{

    public function getMyAddressesWithFees($restaurant_id)
    {
        $restaurant = Restorant::findOrFail($restaurant_id);
        $client = User::where(['api_token' => $_GET['api_token']])->with(['addresses'])->first();
        $addresses = $this->getAccessibleAddresses($restaurant, $client->addresses->reverse());

        if (! $client->addresses->isEmpty()) {

            //For each clinet address calcualte the price

            $okAddress = [];
            foreach ($addresses as $key => $value) {
                array_push($okAddress, $value);
            }

            return response()->json([
                'data' => $okAddress,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'data' => [],
                'status' => false,
                'message'=>'',
                'errMsg' => __("You do not have any address, please add new one."),
            ]);
        }
    }


    public function getMyAddresses()
    {
        $client = User::where(['api_token' => $_GET['api_token']])->with(['addresses'])->first();

        if (! $client->addresses->isEmpty()) {

            //For each clinet address calcualte the price

            return response()->json([
                'data' => $client->addresses,
                'status' => true,
                'errMsg' => '',
            ]);
        } else {
            return response()->json([
                'data' => [],
                'status' => false,
                'message'=>'',
                'errMsg' => __("You don't have any address, please add new one."),
            ]);
        }
    }

    public function makeAddress(Request $request)
    {
        $client = User::where(['api_token' => $request->api_token])->first();

        $address = new Address;
        $address->address = $request->address;
        $address->user_id = $client->id;
        $address->lat = $request->lat;
        $address->lng = $request->lng;
        $address->apartment = $request->apartment ?? $request->apartment;
        $address->intercom = $request->intercom ?? $request->intercom;
        $address->floor = $request->floor ?? $request->floor;
        $address->entry = $request->entry ?? $request->entry;
        $address->save();

        return response()->json([
            'status' => true,
            'id'=>$address->id,
            'message' => __('New address added successfully!'),
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $address_to_delete = Address::where(['id' => $request->id])
        ->where(['user_id'=>auth()->user()->id])->first();

        if ($address_to_delete!=null) {
            $address_to_delete->active = 0;
            $address_to_delete->save();

            return response()->json([
                'status' => true,
                'errMsg' => __('Address successfully deactivated!'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errMsg' => __('You can not delete this address!'),
            ]);
        }
    }
    
}

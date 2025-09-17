<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\KitUser;
use App\Models\Admin\Order_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Customer\Useraddress_model as CustomerUseraddress_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Customeruseraddress extends Controller
{
    public function addAddress(Request $request)
    {
        try {
            $added_data = [
                'user_id' => session('user_id'),
                'address_type' => $request->input('newAddressType'),
                'address_line1' => $request->input('newAddress1'),
                'address_line2' => $request->input('newAddress2'),
                'city' => $request->input('newCity'),
                'state' => $request->input('newState'),
                'country' => $request->input('newCountry'),
                'pincode' => $request->input('newPincode'),
                'is_default' => true
            ];
            CustomerUseraddress_model::where('user_id', session('user_id'))->update(['is_default' => false]);
            CustomerUseraddress_model::create($added_data);


            return response()->json([
                "success" => true,
                'message' => "New address saved successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
        // Handle failed request (4xx or 5xx status codes)
        return [];
    }
}

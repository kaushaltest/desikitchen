<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\KitUser;
use App\Models\Admin\Order_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Admin\Useraddress_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Useraddress extends Controller
{
    public function addNewUser(Request $request)
    {
        try {
            $user = KitUser::create([
                'phone' => $request->input('mobileInput'),
                'name' => $request->input('userNameInput'),
                'email' => $request->input('userEmailInput'),
                'is_created_by_admin'=>true
            ]);

            // Now use the ID from the inserted user
            $added_data = [
                'user_id' => $user->id,  // <-- Get inserted ID
                'address_type' => $request->input('addressTypeInput'),
                'address_line1' => $request->input('address1Input'),
                'address_line2' => $request->input('address2Input'),
                // 'city' => $request->input('cityInput'),
                // 'state' => $request->input('stateInput'),
                // 'country' => $request->input('countryInput'),
                'pincode' => $request->input('pincodeInput'),
                'is_default' => 1
            ];

            $address = Useraddress_model::create($added_data);

            return response()->json([
                "success" => true,
                "user_id" => $user->id,
                "address_id" => $address->id,
                'message' => "Useer account and address added successfully!"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'message' => $e->getMessage()
            ], 200);
        }
        // Handle failed request (4xx or 5xx status codes)
        return [];
    }
    public function addAddress(Request $request)
    {
        try {
            $added_data = [
                'user_id' => $request->input('customer_id'),
                'address_type' => $request->input('newAddressType'),
                'address_line1' => $request->input('newAddress1'),
                'address_line2' => $request->input('newAddress2'),
                // 'city' => $request->input('newCity'),
                // 'state' => $request->input('newState'),
                // 'country' => $request->input('newCountry'),
                'pincode' => $request->input('newPincode')
            ];

            Useraddress_model::create($added_data);

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

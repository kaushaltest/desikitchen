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
            if (empty($request->input('newAddressId'))) {
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
            } else {
                $addressId = $request->input('newAddressId');
                $updateData = [
                    'address_type' => $request->input('newAddressType'),
                    'address_line1' => $request->input('newAddress1'),
                    'address_line2' => $request->input('newAddress2'),
                    // 'city' => $request->input('newCity'),
                    'lat' => $request->input('newLat'),
                    'long' => $request->input('newLng'),
                    'pincode' => $request->input('newPincode'),
                ];

                CustomerUseraddress_model::where('user_id', session('user_id'))->update(['is_default' => false]);
                $updateData['is_default'] = true;

                // Update the selected address
                CustomerUseraddress_model::where('id', $addressId)->update($updateData);

                return response()->json([
                    "success" => true,
                    'message' => "Address updated successfully"
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
        // Handle failed request (4xx or 5xx status codes)
        return [];
    }

    public function deleteAddress(Request $request)
    {
        try {
            $addressId = $request->input('hid_delete_addressid');
    
            if (!$addressId) {
                return response()->json([
                    "success" => false,
                    "message" => "Address ID is required"
                ], 200);
            }
    
            $address = CustomerUseraddress_model::find($addressId);
    
            if (!$address) {
                return response()->json([
                    "success" => false,
                    "message" => "Address not found"
                ], 200);
            }
    
            // Soft delete by setting is_active to false
            $address->is_active = false;
            $address->save();
    
            return response()->json([
                "success" => true,
                "message" => "Address deleted successfully"
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Something went wrong",
                "error" => $e->getMessage()
            ], 200);
        }
    }
    
}

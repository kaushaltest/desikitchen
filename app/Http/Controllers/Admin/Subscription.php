<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Admin\Subscription_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Subscription extends Controller
{
    public function index()
    {
        return view('admin.subscription'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAllSubscription()
    {
        $data = Subscription_model::get();
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function addUpdateSubscription(Request $request)
    {
        try {
            if (empty($request->input('hid_subid'))) {
                $added_data = [
                    'name' => $request->input('txt_name'),
                    'description' => $request->input('txt_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price' => $request->input('txt_price'),
                    'total_meals' => $request->input('txt_meals'),
                    'days' => $request->input('txt_days'),

                ];

                Subscription_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "Subscription added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'name' => $request->input('txt_name'),
                    'description' => $request->input('txt_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price' => $request->input('txt_price'),
                    'total_meals' => $request->input('txt_meals'),
                    'days' => $request->input('txt_days'),
                ];
                $addQuarantine_failed = Subscription_model::updateOrCreate(
                    ['id' => $request->input('hid_subid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Subscription updated successfully"
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

    public function deleteSubscription(Request $request)
    {
        $menu = Subscription_model::find($request->sub_id); // Adjust model name
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'Subscription deleted successfully']);
    }
}

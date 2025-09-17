<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Partyemenu_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class Partymenu extends Controller
{
    public function index()
    {
        return view('admin.partymenu'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getPartyMenuData()
    {
        $data = Partyemenu_model::all()->map(function ($item) {
            // Append full URL to image
            $item->image_url = asset('storage/' . $item->image_path);
            return $item;
        });
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function addUpdateMenu(Request $request)
    {
        try {
            if (empty($request->input('hid_menuid'))) {
                $added_data = [
                    'name' => $request->input('txt_title'),
                    'description' => $request->input('txt_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price_per_kg' => ($request->input('price_unit')=='kg')?$request->input('txt_price'):null,
                    'price_per_qty' => ($request->input('price_unit')=='qty')?$request->input('txt_price'):null,
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/partymenu_images', 'public');
                    $added_data['image_path'] = $imagePath;
                }

                Partyemenu_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New menu item added successfully"
                ], 200);
            } else {
                $updated_data = [
                  'name' => $request->input('txt_title'),
                    'description' => $request->input('txt_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price_per_kg' => ($request->input('price_unit')=='kg')?$request->input('txt_price'):null,
                    'price_per_qty' => ($request->input('price_unit')=='qty')?$request->input('txt_price'):null,
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/partymenu_images', 'public');
                    $updated_data['image_path'] = $imagePath;
                }
                $addQuarantine_failed = Partyemenu_model::updateOrCreate(
                    ['id' => $request->input('hid_menuid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Menu item updated successfully"
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

    public function deletePartyMenu(Request $request)
    {
        $menu = Partyemenu_model::find($request->user_uid); // Adjust model name

        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu not found']);
        }
        // Delete image if exists
        if ($request->has('image_path') && File::exists(public_path('storage/'.$request->image_path))) {
            File::delete(public_path('storage/'.$request->image_path));
        }

        // Or delete from storage if saved via Storage::put
        Storage::delete('public/' . $request->image_path);

        // Delete the menu record
        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu item removed successfully.']);
    }
}

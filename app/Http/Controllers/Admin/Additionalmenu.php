<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Partyemenu_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Additionalmenu extends Controller
{
    public function index()
    {
        return view('admin.additionalmenu'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAdditionalMenuData()
    {
        $data = Additionalmenu_model::all()->map(function ($item) {
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
                    'price' => $request->input('txt_price'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/additionalmenu_images', 'public');
                    $added_data['image_path'] = $imagePath;
                }

                Additionalmenu_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "Menu added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'name' => $request->input('txt_title'),
                    'description' => $request->input('txt_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price' => $request->input('txt_price'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/additionalmenu_images', 'public');
                    $updated_data['image_path'] = $imagePath;
                }
                $addQuarantine_failed = Additionalmenu_model::updateOrCreate(
                    ['id' => $request->input('hid_menuid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Menu updated successfully"
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
        $menu = Additionalmenu_model::find($request->user_uid); // Adjust model name

        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu not found']);
        }
        // Delete image if exists
        if ($request->has('image_path') && File::exists(public_path('storage/' . $request->image_path))) {
            File::delete(public_path('storage/' . $request->image_path));
        }

        // Or delete from storage if saved via Storage::put
        Storage::delete('public/' . $request->image_path);

        // Delete the menu record
        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu deleted successfully']);
    }
}

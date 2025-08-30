<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Daywisemenu_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Daywisemenu extends Controller
{
    public function index()
    {
        return view('admin.daywisemenu'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getDaywiseMenuData()
    {
        $data = Daywisemenu_model::all()->map(function ($item) {
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
                    'menu_date' => $request->input('dt_date'),
                    'title' => $request->input('txt_title'),
                    'items' => $request->input('txt_item'),
                    'price' => $request->input('txt_price'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/menu_images', 'public');
                    $added_data['image_path'] = $imagePath;
                }

                Daywisemenu_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "Menu added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'menu_date' => $request->input('dt_date'),
                    'title' => $request->input('txt_title'),
                    'items' => $request->input('txt_item'),
                    'price' => $request->input('txt_price')
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/menu_images', 'public');
                    $updated_data['image_path'] = $imagePath;
                }
                $addQuarantine_failed = Daywisemenu_model::updateOrCreate(
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

    public function deleteDayWiseMenu(Request $request)
    {
        $menu = Daywisemenu_model::find($request->user_uid); // Adjust model name

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

        return response()->json(['success' => true, 'message' => 'Menu deleted successfully']);
    }
}

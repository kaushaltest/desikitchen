<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MenuExport;
use App\Http\Controllers\Controller;
use App\Imports\MenuImport;
use App\Models\Admin\Daywisemenu_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
                    'is_active' => $request->input('rbt_is_active'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/menu_images', 'public');
                    $added_data['image_path'] = $imagePath;
                }

                Daywisemenu_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New menu item added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'menu_date' => $request->input('dt_date'),
                    'title' => $request->input('txt_title'),
                    'items' => $request->input('txt_item'),
                    'price' => $request->input('txt_price'),
                    'is_active' => $request->input('rbt_is_active'),
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

    public function deleteDayWiseMenu(Request $request)
    {
        // $menu = Daywisemenu_model::find($request->user_uid); // Adjust model name

        // if (!$menu) {
        //     return response()->json(['success' => false, 'message' => 'Menu not found']);
        // }
        // // Delete image if exists
        // if ($request->has('image_path') && File::exists(public_path('storage/' . $request->image_path))) {
        //     File::delete(public_path('storage/' . $request->image_path));
        // }

        // // Or delete from storage if saved via Storage::put
        // Storage::delete('public/' . $request->image_path);

        // // Delete the menu record
        // $menu->delete();

        // return response()->json(['success' => true, 'message' => 'Menu item removed successfully.']);
        $existingMenu = Daywisemenu_model::find($request->user_uid);

        if ($existingMenu) {
            // Update is_delete to true
            $existingMenu->is_active = ($existingMenu->is_active)?false:true;
            $existingMenu->save();

            return response()->json([
                'success' => true,
                'message' => 'Menu item removed successfully.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not removed.'
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $data = $request->input('data', []);
            foreach ($data as $row) {
                $imagePath = null;
                // If image exists
                if (!empty($row['image'])) {
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $row['image']));
                    $imageName = uniqid() . '.png';
                    Storage::disk('public')->put("uploads/$imageName", $imageData);
                    $imagePath = "uploads/$imageName";
                }
                if (empty($row['title']) || empty($row['price'] || empty($row['date']))) {
                    continue;
                }
                $menuDate = $this->normalizeDate($row['date']);
                if (!empty($row['date'])) {
                    $menu = Daywisemenu_model::whereDate('menu_date', $menuDate)   // compares only the date part
                    ->first();
                    
                    if ($menu) {
                        // Update existing record
                        $menu->update([
                            'title' => $row['title'],
                            'price' => $row['price'],
                            'menu_date'  => $menuDate,
                            'items' => $row['items'],
                            'image' => $imagePath ?? $menu->image, // keep old image if not provided
                        ]);
                    } else {
                        // ID given but not found → insert new
                        Daywisemenu_model::create([
                            'title' => $row['title'],
                            'price' => $row['price'],
                            'menu_date'  => $menuDate,
                            'items' => $row['items'],
                            'image' => $imagePath ?? null,
                        ]);
                    }
                } else {
                    // No ID → always create new
                    Daywisemenu_model::create([
                        'title' => $row['title'],
                        'price' => $row['price'],
                        'menu_date'  =>$menuDate,
                        'items' => $row['items'],
                        'image' => $imagePath ?? null,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'File imported successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }
    private function normalizeDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }
    
        try {
            // Case 1: Already a Carbon/DateTime object
            if ($dateValue instanceof \DateTime) {
                return Carbon::instance($dateValue)->format('Y-m-d');
            }
    
            // Case 2: Numeric Excel date serial
            if (is_numeric($dateValue)) {
                // Excel's base date is 1899-12-30
                return Carbon::createFromDate(1899, 12, 30)->addDays($dateValue)->format('Y-m-d');
            }
    
            // Case 3: ISO 8601 string or normal string
            return Carbon::parse($dateValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // fallback if parsing fails
        }
    }
    
    public function export()
    {
        return Excel::download(new MenuExport, 'menus.xlsx');
    }
}

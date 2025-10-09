<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class Alacartemenu extends Controller
{
    public function index()
    {
        return view('admin.alacartemenu'); // View: resources/views/admin/dashboard.blade.php
    }
    public function category()
    {
        return view('admin.category'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAlacarteMenuData()
    {
        $data = Alacartemenu_model::with(['category'])->get()->map(function ($item) {
            // Append full URL to image
            $item->category_name = $item->category ? $item->category->category : null;
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
                    'category_id' => $request->input('drp_category'),
                    'name' => $request->input('txt_title'),
                    'description' => $request->input('text_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price' => $request->input('txt_price'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/alacartemenu_images', 'public');
                    $added_data['image_path'] = $imagePath;
                }

                Alacartemenu_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New menu item added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'category_id' => $request->input('drp_category'),
                    'name' => $request->input('txt_title'),
                    'description' => $request->input('text_description'),
                    'is_active' => $request->input('rbt_is_active'),
                    'price' => $request->input('txt_price'),
                ];
                if ($request->hasFile('file_menu_image')) {
                    $imagePath = $request->file('file_menu_image')->store('uploads/alacartemenu_images', 'public');
                    $updated_data['image_path'] = $imagePath;
                }
                $addQuarantine_failed = Alacartemenu_model::updateOrCreate(
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

    public function deleteAlacarteMenu(Request $request)
    {
        $menu = Alacartemenu_model::find($request->user_uid); // Adjust model name

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

        return response()->json(['success' => true, 'message' => 'Menu item removed successfully.']);
    }

    public function getCategory()
    {
        $data = Category_model::select('id', 'category', 'is_delete')->get();
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function addCategory(Request $request)
    {
        try {
            $categoryName = $request->input('txt_category');

            $exists = Category_model::whereRaw('LOWER(category) = ?', [strtolower($categoryName)])->exists();

            if ($exists) {
                return response()->json([
                    "success" => false,
                    'message' => "This category name is already in use.eady exists"
                ], 200);
            }
            $added_data = [
                'category' => $categoryName,
                'is_delete' => $request->input('rbt_is_active'),
            ];

            Category_model::create($added_data);

            return response()->json([
                "success" => true,
                'message' => "New category created successfully!essfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function addEditCategory(Request $request)
    {
        try {
            $categoryName = $request->input('txt_category');

          
            $added_data = [
                'category' => $categoryName,
                'is_delete' => $request->input('rbt_is_active'),
            ];

            if (!empty($request->input('hid_menuid'))) {
                $exists = Category_model::whereRaw('LOWER(category) = ?', [strtolower($categoryName)])->where('id', '!=', $request->input('hid_menuid'))->exists();

                if ($exists) {
                    return response()->json([
                        "success" => false,
                        'message' => "This category name is already in use & exists"
                    ], 200);
                }
                $category = Category_model::where('id', $request->input('hid_menuid'))->first();
                if ($category) {
                    $category->update($added_data);
                    return response()->json([
                        "success" => true,
                        'message' => "Category updated successfully"
                    ], 200);
                } else {
                    Category_model::create($added_data);
                    return response()->json([
                        "success" => true,
                        'message' => "New category created successfully"
                    ], 200);
                }
            } else {
                $exists = Category_model::whereRaw('LOWER(category) = ?', [strtolower($categoryName)])->exists();

                if ($exists) {
                    return response()->json([
                        "success" => false,
                        'message' => "This category name is already in use & exists"
                    ], 200);
                }
                Category_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New category created successfully"
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function deleteCategory(Request $request)
    {
        try {
            $category = Category_model::find($request->category_id); // Adjust model name

            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Category not found']);
            }
            $category->delete();

            return response()->json(['success' => true, 'message' => 'Category removed successfully.']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a foreign key constraint error
            if ($e->getCode() == "23000") {
                return response()->json([
                    "success" => false,
                    "message" => "This category cannot be deleted because it is linked to one or more menus."
                ], 200);
            }

            // Other DB errors
            return response()->json([
                "success" => false,
                "message" => "Database error: " . $e->getMessage()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function import(Request $request)
    {
        try {
            $data = $request->input('data', []);

            foreach ($data as $row) {
                $imagePath = null;

                if (empty($row['name']) || empty($row['category']) || empty($row['price'])) {
                    continue;
                }

                if (!empty($row['category']) || empty($row['name'])) {
                    $menu = Alacartemenu_model::where('category_id', $row['category'])->where('name', $row['name'])   // compares only the date part
                        ->first();

                    if ($menu) {
                        // Update existing record
                        $menu->update([
                            'category_id' => $row['category'],
                            'name' => $row['name'],
                            'description' => $row['description'],
                            'price' => $row['price'],
                            'is_active'  => $row['is_active'],
                        ]);
                    } else {
                        // ID given but not found → insert new
                        Alacartemenu_model::create([
                            'category_id' => $row['category'],
                            'name' => $row['name'],
                            'description' => $row['description'],
                            'price' => $row['price'],
                            'image' => $imagePath ?? null,
                            'is_active'  => $row['is_active']
                        ]);
                    }
                } else {
                    // No ID → always create new
                    Alacartemenu_model::create([
                        'category_id' => $row['category'],
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'price' => $row['price'],
                        'image' => $imagePath ?? null,
                        'is_active'  => $row['is_active']
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
}

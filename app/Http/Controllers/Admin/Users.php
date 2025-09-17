<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Admin\KitUser;
use App\Models\Customer\Table_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Users extends Controller
{
    public function index()
    {
        return view('admin.users'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAllUser()
    {
        $data = KitUser::whereIn('role', ['user', 'guest'])->get();
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function addUpdateUser(Request $request)
    {
        try {
            if (empty($request->input('hid_userid'))) {
                $existingUser = KitUser::where('phone', $request->input('txt_phone'))->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This mobile number is already associated with an account.registered.',
                    ]);
                }
                $added_data = [
                    'name' => $request->input('txt_name'),
                    'email' => $request->input('txt_email'),
                    'phone' => $request->input('txt_phone'),
                    'is_active' => $request->input('rbt_is_active'),
                    'role' => 'user'

                ];

                KitUser::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New user account created successfully!"
                ], 200);
            } else {
                $userId = $request->input('hid_userid');
                $existingUser = KitUser::where('phone', $request->input('txt_phone'))
                    ->when(!empty($userId), function ($query) use ($userId) {
                        $query->where('id', '!=', $userId);
                    })
                    ->first();

                if ($existingUser) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This mobile number is already associated with an account.registered by another user.',
                    ]);
                }
                $updated_data = [
                    'name' => $request->input('txt_name'),
                    'email' => $request->input('txt_email'),
                    'phone' => $request->input('txt_phone'),
                    'is_active' => $request->input('rbt_is_active'),

                ];
                $addQuarantine_failed = KitUser::updateOrCreate(
                    ['id' => $request->input('hid_userid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Your profile has been updated successfully."
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

    public function deleteUser(Request $request)
    {
        $menu = KitUser::find($request->sub_id); // Adjust model name
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'User account has been successfully removed.successfully']);
    }

    public function adminUser()
    {
        return view('admin.adminuser'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAllAdminUser()
    {
        $data = KitUser::whereIn('role', ['admin'])->get();
        return response()->json([
            'data' => $data,
        ], 200);
    }
    public function addUpdateAdminUser(Request $request)
    {
        try {
            if (empty($request->input('hid_userid'))) {
                $added_data = [
                    'name' => $request->input('txt_name'),
                    'email' => $request->input('txt_email'),
                    'password' => Hash::make($request->input('txt_password')),
                    'phone' => $request->input('txt_phone'),
                    'is_active' => $request->input('rbt_is_active'),
                    'role' => 'admin'

                ];

                KitUser::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "New user account created successfully!"
                ], 200);
            } else {
                $updated_data = [
                    'name' => $request->input('txt_name'),
                    'email' => $request->input('txt_email'),
                    'password' =>  Hash::make($request->input('txt_password')),
                    'phone' => $request->input('txt_phone'),
                    'is_active' => $request->input('rbt_is_active'),

                ];
                $addQuarantine_failed = KitUser::updateOrCreate(
                    ['id' => $request->input('hid_userid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Your profile has been updated successfully."
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
    public function deleteAdminUser(Request $request)
    {
        $menu = KitUser::find($request->sub_id); // Adjust model name
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'User account has been successfully removed.successfully']);
    }

    public function tables()
    {
        return view('admin.table'); // View: resources/views/admin/dashboard.blade.php
    }

    public function getAllTables()
    {
        $data = Table_model::get();
        return response()->json([
            'success'=>true,
            'data' => $data,
        ], 200);
    }
    public function addUpdateTables(Request $request)
    {
        try {
            if (empty($request->input('hid_tableid'))) {
                $added_data = [
                    'name' => $request->input('txt_name'),
                    'capicity' => $request->input('txt_capicity'),
                    'is_active' => $request->input('rbt_is_active'),
                ];

                Table_model::create($added_data);
                return response()->json([
                    "success" => true,
                    'message' => "New table added successfully!"
                ], 200);
            } else {
                $updated_data = [
                    'name' => $request->input('txt_name'),
                    'capicity' => $request->input('txt_capicity'),
                    'is_active' => $request->input('rbt_is_active'),
                ];
                Table_model::updateOrCreate(
                    ['id' => $request->input('hid_tableid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Table details updated successfully."
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
    public function deleteTables(Request $request)
    {
        $menu = KitUser::find($request->sub_id); // Adjust model name
        $menu->delete();
        return response()->json(['success' => true, 'message' => 'Table removed successfully.']);
    }
}

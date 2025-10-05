<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Admin\KitUser;
use App\Models\Customer\Table_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Tables extends Controller
{
    public function index()
    {
        return view('admin.selecttable'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAllUser()
    {
        $data = KitUser::whereIn('role', ['user', 'guest'])->get();
        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function tables()
    {
        return view('admin.table'); // View: resources/views/admin/dashboard.blade.php
    }

    public function getAllTables()
    {
        $data = Table_model::get();
        return response()->json([
            'success' => true,
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

    public function tableOrder()
    {
        return view('admin.tableorder');
    }

    public function bookTable(Request $res)
    {
        $table = Table_model::find($res->table_id);

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found.'
            ]);
        }

        // Case 1: Table is free → allow booking
        if (is_null($table->user_id)) {
            $table->user_id = Auth::user()->id;
            $table->save();

            return response()->json([
                'success' => true,
                'message' => 'Table reserved successfully!.',
                'table_id' => $table->id,
            ]);
        }

        // Case 2: Table already booked by this user → allow edit
        if ($table->user_id == Auth::user()->id) {
            return response()->json([
                'success' => true,
                'message' => 'This table is already reserved. this table.',
                'table_id' => $table->id,
            ]);
        }

        // Case 3: Table booked by someone else → block
        return response()->json([
            'success' => false,
            'message' => 'This table is already reserved by someone else. by another user.',
        ]);
    }

    public function releaseTable(Request $res)
    {
        $table = Table_model::find($res->table_id);

        if (!$table) {
            return response()->json([
                'success' => false,
                'message' => 'Table not found.'
            ]);
        }

        // Case 1: Table is free → allow booking
        if (is_null($table->user_id)) {
            $table->user_id = Auth::user()->id;
            $table->save();

            return response()->json([
                'success' => true,
                'message' => 'Table reserved successfully!.',
                'table_id' => $table->id,
            ]);
        }

        // Case 2: Table already booked by this user → allow edit
        if ($table->user_id == Auth::user()->id) {
            $table->user_id = null;
            $table->save();
            return response()->json([
                'success' => true,
                'message' => 'This table release successfully.',
            ]);
        }

        // Case 3: Table booked by someone else → block
        return response()->json([
            'success' => false,
            'message' => 'This table is already reserved by someone else. by another user.',
        ]);
    }
}

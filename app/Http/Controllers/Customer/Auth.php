<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Auth_model;
use Illuminate\Http\Request;

class Auth extends Controller
{
    public function checkMobileExist(Request $request)
    {
        $mobile = $request->input('txt_mobile');
        $data = Auth_model::where('phone', $mobile)
            ->first();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $otp = $request->input('otp');
        $mobile = $request->input('mobile');

        if ($otp == '1234') {
            $data = Auth_model::where('phone', $mobile)
                ->first();
            session([
                'user_id' => $data->id,
                'user_phone' => $data->phone,
                'user_name' => $data->name,
                'user_email' => $data->email,
                // any other needed payload
            ]);

            // Regenerate for safety
            session()->regenerate();
            return response()->json([
                "success" => true,
                "message" => 'OTP verified successfuly'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function verifyGuestOTP(Request $request)
    {
        $otp = $request->input('otp');
        $mobile = $request->input('mobile');

        if ($otp == '1234') {
            $data = Auth_model::where('phone', $mobile)
                ->first();
            if (!$data) {
                // 🚀 If not exist, create new guest user
                $data = Auth_model::create([
                    'phone' => $mobile,
                    'name'  => 'Guest User', // you can make this dynamic if needed
                ]);
            }

            session([
                'user_id' => $data->id,
                'user_phone' => $data->phone,
                'user_name' => $data->name,
                'user_email' => $data->email,
                // any other needed payload
            ]);

            // Regenerate for safety
            session()->regenerate();
            return response()->json([
                "success" => true,
                "message" => 'OTP verified successfuly'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function checkRegisterMobileExist(Request $request)
    {
        $mobile = $request->input('txt_new_mobile');
        $data = Auth_model::where('phone', $mobile)
            ->first();
        if ($data) {
            return response()->json([
                "success" => false,
                "message" => 'This mobile number is already exist'
            ]);
        }
        return response()->json([
            "success" => true
        ]);
    }

    public function verifyRegisterOTP(Request $request)
    {
        $otp = $request->input('otp');
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        if ($otp == '1234') {
            $data = Auth_model::create([
                'name'  => $name,
                'email' => $email,
                'phone' => $mobile,
                // add any other default fields like password, etc.
            ]);
            session([
                'user_id' => $data->id,
                'user_phone' => $mobile,
                'user_name' => $name,
                'user_email' => $email,
                // any other needed payload
            ]);

            // Regenerate for safety
            session()->regenerate();
            return response()->json([
                "success" => true,
                "message" => 'OTP verified successfuly'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Invalid OTP. Please try again.'
            ]);
        }
    }

    public function sessionExists(Request $request): bool
    {
        return $request->session()->has('user_id');
    }
    public function custSessionExists(Request $request): bool
    {
        return $request->session()->has('user_id');
    }

    public function getUserAddress(Request $request)
    {
        $mobile = session('user_phone');
        $data = Auth_model::where('phone', $mobile)
            ->with('address') // just load address normally, no need to filter it
            ->first(); // since mobile should be unique
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function updateUser(Request $request)
    {
        $userId = session('user_id'); // get logged-in user from session

        if (!$userId) {
            return response()->json([
                "success" => false,
                "message" => "User not logged in."
            ], 401);
        }
        $user = Auth_model::find($userId);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found."
            ], 404);
        }

        // ✅ Update user info
        $user->name  = $request->input('txt_edit_name');
        $user->email = $request->input('txt_edit_email');
        $user->save();

        // ✅ Update session too
        session([
            'user_name'  => $user->name,
            'user_email' => $user->email,
        ]);

        return response()->json([
            "success" => true,
            "message" => "User updated successfully.",
            "data"    => $user
        ]);
    }
}

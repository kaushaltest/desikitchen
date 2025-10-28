<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Auth_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;

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

    public function signIn(Request $request)
    {
        $email = $request->input('txt_login_email');
        $password = $request->input('txt_login_password');

        $user = Auth_model::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found with this email."
            ], 200);
        }

        // 3. Verify password
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Invalid password."
            ], 200);
        }
        session([
            'user_id' => $user->id,
            'user_phone' => $user->phone,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_code' => $user->country_code
            // any other needed payload
        ]);

        // Regenerate for safety
        session()->regenerate();
        return response()->json([
            "success" => true,
            "message" => 'Login successfuly'
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
                "message" => 'OTP verification completed successfully!'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'The OTP entered is incorrect. Please try again.. Please try again.'
            ]);
        }
    }

    public function verifyGuestOTP(Request $request)
    {
        $otp = $request->input('otp');
        $mobile = $request->input('mobile');
        $code  = $request->input('code');
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/VerificationCheck", [
                'To' => $code . $mobile,
                'Code' => $otp
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'The OTP entered is incorrect. Please try again.',  // Full Twilio error details
            ], 200);
        }
        $data = $response->json();

        if (isset($data['status']) && $data['status'] === 'approved') {
            $data = Auth_model::where('phone', $mobile)
                ->first();
            if (!$data) {
                // 🚀 If not exist, create new guest user
                $data = Auth_model::create([
                    'phone' => $mobile,
                    'name'  => 'Guest User', // you can make this dynamic if needed
                    'country_code' => $code,
                    'role' => 'guest'
                ]);
            }

            session([
                'user_id' => $data->id,
                'user_phone' => $data->phone,
                'user_name' => $data->name,
                'user_email' => $data->email,
                'user_code' => $code
                // any other needed payload
            ]);

            // Regenerate for safety
            session()->regenerate();
            return response()->json([
                "success" => true,
                "message" => 'OTP verification completed successfully!'
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'The OTP entered is incorrect. Please try again.. Please try again.'
            ]);
        }
    }

    public function guestLoginOTP(Request $request)
    {
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/Verifications", [
                'To' => $code . $mobile,
                'Channel' => 'sms'
            ]);
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Twilio API returned an error.',
                'twilio_error' => $response->json(),
            ], $response->status());
        }

        $data = $response->json();

        // Check if OTP request was successful
        if (isset($data['status']) && in_array($data['status'], ['pending', 'sent'])) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully!',
                'twilio_response' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP.',
                'twilio_response' => $data
            ], 200);
        }
    }

    public function checkRegisterMobileExist(Request $request)
    {
        $email = $request->input('txt_new_email');
        $mobile = $request->input('txt_new_mobile');
        $code = $request->input('txt_new_countrycode');
        $data = Auth_model::where('email', $email)->where('is_created_by_admin', false)
            ->first();
        if ($data) {
            return response()->json([
                "success" => false,
                "message" => 'This email is already exist'
            ]);
        }

        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/Verifications", [
                'To' => $code . $mobile,      // e.g. +919876543210
                'Channel' => 'sms'
            ]);
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Twilio API returned an error.',
                'twilio_error' => $response->json(),
            ], $response->status());
        }

        $data = $response->json();

        // Check if OTP request was successful
        if (isset($data['status']) && in_array($data['status'], ['pending', 'sent'])) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully!',
                'twilio_response' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP.',
                'twilio_response' => $data
            ], 200);
        }
    }

    public function verifyRegisterOTP(Request $request)
    {
        $otp = $request->input('otp');
        $name = $request->input('name');
        $email = $request->input('email');
        $mobile = $request->input('mobile');
        $password = $request->input('password');
        $country_code = $request->input('country_code');
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/VerificationCheck", [
                'To' => $country_code . $mobile,
                'Code' => $otp
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'The OTP entered is incorrect. Please try again.. Please try again.',  // Full Twilio error details
            ], 200);
        }
        $data = $response->json();

        if (isset($data['status']) && $data['status'] === 'approved') {


            $user = Auth_model::where('phone', $mobile)
                ->where('role', 'guest')
                ->first();


            if ($user) {
                // 2. Update existing guest record
                $user->update([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => Hash::make($password),
                    'role'     => 'user', // or whatever role you want after registration
                ]);
            } else {

                $isadmincreated = Auth_model::where('email', $email)->where('is_created_by_admin', true)
                    ->first();

                if ($isadmincreated) {
                    $user->update([
                        'name'     => $name,
                        'email'    => $email,
                        'password' => Hash::make($password),
                        'role'     => 'user',
                        'is_created_by_admin' => false
                    ]);
                }
                // 3. Otherwise create new record
                $user = Auth_model::create([
                    'name'     => $name,
                    'email'    => $email,
                    'phone'    => $mobile,
                    'country_code'  => $country_code,
                    'password' => Hash::make($password),
                    'role'     => 'user', // default role
                ]);
            }

            session([
                'user_id'    => $user->id,
                'user_phone' => $user->phone,
                'user_name'  => $user->name,
                'user_email' => $user->email,
                'user_code' => $country_code
            ]);
            // OTP is valid, do your registration/login logic here

            // Regenerate session for safety
            session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'User verified successfully!',
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'The OTP entered is incorrect. Please try again.. Please try again.'
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
        $userId = session('user_id');
        $data = Auth_model::where('id', $userId)
            ->with(['address' => function ($query) {
                $query->where('is_active', 1);
            }])
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
        $existingUser = Auth_model::where('email', $request->input('txt_edit_email'))
            ->when(!empty($userId), function ($query) use ($userId) {
                $query->where('id', '!=', $userId);
            })
            ->first();

        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already associated with an account.',
            ]);
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
            "message" => "Your profile has been updated successfully..",
            "data"    => $user
        ]);
    }

    public function sendOtp(Request $request)
    {
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/Verifications", [
                'To' => '+91' . $request->phone,      // e.g. +919876543210
                'Channel' => 'sms'
            ]);

        return $response->json();
    }

    public function verifySMPOtp(Request $request)
    {
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://verify.twilio.com/v2/Services/" . env('TWILIO_VERIFY_SID') . "/VerificationCheck", [
                'To' => $request->phone,
                'Code' => $request->otp
            ]);

        return $response->json();
    }

    public function sendCustomSms(Request $request)
    {
        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                'To' => $request->phone,
                'From' => env('TWILIO_PHONE'),
                'Body' => $request->message
            ]);

        return $response->json();
    }

    public function forgotPassword()
    {
        return view('customer.forget-password');
    }
    public function create($token)
    {
        return view('customer.reset-password', ['token' => $token]);
    }

    public function requestPassword(Request $request)
    {

        $request->validate(['txt_forgot_email' => 'required|email']);

        $status = Password::sendResetLink([
            'email' => $request->input('txt_forgot_email')
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => __($status)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __($status)
            ], 200); // 422 = validation/processing error
        }
    }
    public function store(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.dashboard')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}

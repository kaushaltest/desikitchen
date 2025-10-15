<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\KitUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function forgotPassword()
    {
        return view('auth.forget-password');
    }
    public function create($token)
    {
        return view('auth.reset-password',['token' => $token]);
    }

    public function requestPassword(Request $request)
    {
      
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
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
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'super-admin' || Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return redirect()->back()->with([
                'status'=>'danger',
                'message' => 'Access denied. Not an admin account.',
            ]);
        }

        return back()->with([
            'status'=>'danger',
            'message' => 'Incorrect username or password. Please try again..',
        ]);
    }
    public function checkMobileExist(Request $request)
    {
        $mobile = $request->input('mobile');
        $data = KitUser::where('phone', $mobile)
        ->with(['address' => function ($query) {
            $query->where('is_active', 1);
        }])
            ->first(); // since mobile should be unique
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('admin/');
    }
}

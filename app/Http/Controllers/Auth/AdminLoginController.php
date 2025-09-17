<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\KitUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
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
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Access denied. Not an admin account.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Incorrect username or password. Please try again..',
        ]);
    }
    public function checkMobileExist(Request $request)
    {
        $mobile = $request->input('mobile');
        $data = KitUser::where('phone', $mobile)
            ->with('address') // just load address normally, no need to filter it
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

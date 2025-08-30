<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\KitUser;
use App\Models\Admin\Order_model;
use App\Models\Admin\Subscription_model;
use App\Models\Admin\UserSubscription_model;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard'); // View: resources/views/admin/dashboard.blade.php
    }

    public function getInfoCardData()
    {
        $data = [];
        $today = Carbon::today(); // Get today's date
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday by default
        $endOfWeek   = Carbon::now()->endOfWeek();   // Sunday by default
        // $data['total_order'] = Order_model::count();
        $data['pending_order'] = Order_model::where('status', '=', 'pending')
            ->when(Auth::user()->role === 'admin', function ($query) {
                // If role = admin, add condition
                return $query->where('created_by', Auth::id());
            })
            ->count();
        // $data['completed_order'] = Order_model::where('status', '=', 'delivered')->count();
        // $data['cancelled_order'] = Order_model::where('status', '=', 'cancelled')->count();
        // $data['total_user'] = KitUser::where(function ($query) {
        //     $query->where('role', 'user')->orWhere('role', 'guest');
        // })->count();
        $data['today_order'] = Order_model::where('order_date', '=', $today)->when(Auth::user()->role === 'admin', function ($query) {
            // If role = admin, add condition
            return $query->where('created_by', Auth::id());
        })
            ->count();
        $data['today_revenue'] = Order_model::where('status', 'delivered')
            ->whereDate('order_date', Carbon::today())
            ->when(Auth::user()->role === 'admin', function ($query) {
                // If role = admin, add condition
                return $query->where('created_by', Auth::id());
            })
            ->sum('total_amount');
        $data['weekly_revenue'] = Order_model::where('status', 'delivered')
            ->whereBetween('order_date', [$startOfWeek, $endOfWeek])
            ->when(Auth::user()->role === 'admin', function ($query) {
                // If role = admin, add condition
                return $query->where('created_by', Auth::id());
            })
            ->sum('total_amount');
        $data['active_subscription'] = UserSubscription_model::where('end_date', '>=', $today)->count();

        // $data['daywise'] = $daywisemenu;
        // $alacartemenu = Category_model::with(['alacartemenus' => function ($query) {
        //     $query->where('is_active', true);
        // }])->get();
        // $data['alacarte'] = $alacartemenu;
        // $partymenu = Partyemenu_model::where('is_active', '=', true)
        //     // ->orderBy('menu_date', 'asc')
        //     ->get();
        // $data['party'] = $partymenu;
        // $additionalmenu = Additionalmenu_model::where('is_active', '=', true)
        //     // ->orderBy('menu_date', 'asc')
        //     ->get();
        // $data['additional_menu'] = $additionalmenu;
        return response()->json([
            "success" => true,
            "data" => $data
        ]); // Return as JSON
    }
}

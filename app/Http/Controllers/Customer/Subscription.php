<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\Order_model;
use App\Models\Admin\Orderitem_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Customer\Auth_model;
use App\Models\Customer\Customerorder_model;
use App\Models\Customer\Subscription_model;
use App\Models\Customer\Usersubscription_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Subscription extends Controller
{

    public function index()
    {
        $subscription = Subscription_model::get();

        return view('customer.subscription', ['subscription' => $subscription]);
    }

    public function profile()
    {
        $profile = Auth_model::with(['subscription.plan'])
            ->where('id', session('user_id'))
            ->first();
        // print_r($profile->toArray());
        // exit;
        return view('customer.profile', ['profile' => $profile->toArray()]);
    }

    public function buySubscription(Request $request)
    {
        try {
            if ($request->session()->has('user_id')) {
                $userId = session('user_id');

                $planId   = $request->input('plan_id');
                $existing = Usersubscription_model::where('user_id', $userId)->first();

                if ($existing) {
                    $endDate = Carbon::parse($existing->end_date);
                    $today = Carbon::today();
                    // check if plan is still active
                    if ($endDate->gte($today)  && $existing->meals_remaining > 0) {
                        return response()->json([
                            'success' => false,
                            'loggedin' => true,
                            'message' => 'You already have an active plan. You cannot purchase another one right now.'
                        ], 200);
                    }else{
                        return response()->json([
                            'success' => false,
                            'loggedin' => false
                        ]);
                    }
                    
                } 
                return response()->json([
                    'success' => false,
                    'loggedin' => false
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'loggedin' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'loggedin' => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }

    public function checkSubscription(Request $request)
    {
        try {
            if ($request->session()->has('user_id')) {
                $userId = session('user_id');
                $existing = Usersubscription_model::where('user_id', $userId)->first();

                if ($existing) {
                    $endDate = Carbon::parse($existing->end_date);
                    $today = Carbon::today();
                    // check if plan is still active
                    if ($endDate->gte($today)  && $existing->meals_remaining > 0) {
                        return response()->json([
                            'success' => true,
                            'is_subscription'=>true,
                            'meal_remaining' => $existing->meals_remaining
                        ], 200);
                    }
                    return response()->json([
                        'success' => false,
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'is_subscription'=>false,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }
}

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
            $userId = session('user_id');

            $planId   = $request->input('plan_id');
            $meals    = $request->input('meal');
            $days = (int) $request->input('days');   // cast to int

            // Ensure Asia/Kolkata timezone
            $startDate = Carbon::now('Asia/Kolkata');
            if ($days) {
                $endDate   = Carbon::now('Asia/Kolkata')->addDays($days);
            }

            $existing = Usersubscription_model::where('user_id', $userId)->first();

            $data = [
                'user_id'        => $userId,
                'plan_id'         => $planId,
                'meal_remaining' => $meals,
                'start_date'     => $startDate,
                'end_date'       => ($days) ? $endDate : null,
            ];

            if ($existing) {
                $existing->update($data);
                $msg = "Subscription updated successfully!";
            } else {
                Usersubscription_model::create($data);
                $msg = "Subscription created successfully!";
            }

            return response()->json([
                'success' => true,
                'message' => $msg,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
    }
}

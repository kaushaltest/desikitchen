<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Additionalmenu_model;
use App\Models\Customer\Alacartemenu_model;
use App\Models\Customer\Customerorder_model;
use App\Models\Customer\Customerorderitem_model;
use App\Models\Customer\Daywisemenu_model;
use App\Models\Customer\Subscription_meal_log_model;
use App\Models\Customer\Subscription_model;
use App\Models\Customer\Usersubscription_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class Customerorder extends Controller
{
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
    public function index()
    {
        return view('customer.order'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getOrder()
    {
        $orders = Customerorder_model::with(['items', 'address'])->orderBy('order_date', 'asc')->where('user_id', session('user_id'))->get();
        $data = $orders->map(function ($order) {
            $itemHtmlList = '';
            $menuTitle = '';
            $total_amount = 0;
            // echo "<pre>";
            // print_r($order->toArray());
            // echo "</pre>";
            // exit;
            // print_r($order->toArray());
            foreach ($order->items as $item) {
                $itemHtmlList .= '<div class="order-item d-flex justify-content-between">';
                switch ($item->item_type) {
                    case 'daywise':
                        // $existing = Usersubscription_model::where('user_id', session('user_id'))->first();
                        $menu = Daywisemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $unit = 'Qty';
                            $total_amount = $item->total_price;
                            $menuTitle = $menu->title;
                        }
                        break;

                    case 'additional':
                        $menu = Additionalmenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $unit = 'Qty';
                            $total_amount = $price * $item->quantity;
                            $menuTitle = $menu->name;
                        }
                        break;
                    case 'alacarte':
                        $menu = Alacartemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $unit = 'Qty';
                            $total_amount = $price * $item->quantity;
                            $menuTitle = $menu->name;
                        }
                        break;
                    case 'subscription':
                        // $existing = Usersubscription_model::where('user_id', session('user_id'))->first();
                        $sub = Subscription_model::find($item->item_id);
                        if ($sub) {
                            $unit = 'Qty';
                            $total_amount = $item->total_price;
                            $menuTitle = $sub->name;
                        }
                        break;
                    default:
                        $menuTitle = "Unknown Item #{$item->item_id}";
                        $price = 0;
                        $unit = 'Qty';
                        $total_amount = 0;
                        break;
                }
                if (isset($menuTitle)) {
                    $itemHtmlList .= '
                     <span class="text-sm">' . number_format($item->quantity, 0) . 'x ' . e($menuTitle) . '</span>
                     <span class="text-sm ">$' . number_format($total_amount, 2) . '</span>';
                }
                $itemHtmlList .= '</div>';
            }

            return [
                'id'            => $order->id,
                'address' => $order->address
                    ? "<b>{$order->address->address_type}</b>:,{$order->address->address_line1}, {$order->address->address_line2}, {$order->address->city} - {$order->address->pincode}."
                    : 'N/A',
                'items'         => $itemHtmlList,
                'order_id' => $order->order_id,
                'order_type'    => ucfirst($order->order_type),
                'order_date'    =>  Carbon::parse($order->order_date)->format('d-m-Y'),
                'note' => $order->note,
                'payment_status' => $order->payment_status,
                'status'        => ucfirst($order->status),
                'total_amount'  => $order->total_amount,
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
                'current_date' => Carbon::today('Asia/Kolkata')->format('d-m-Y'),
                'delivered_at' => Carbon::parse($order->updated_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json($data);
    }
    public function getOrderListData() {}

    public function addOrder(Request $request)
    {
        try {
            $items = $request->input('cart');

            if (isset($items['subscription']) && (!empty($items['subscription']) && empty($items['daywise']) && empty($items['alacarte']))) {
                if ($request->session()->has('user_id')) {
                    $userId = session('user_id');
                    $planId = $items['subscription'][0]['db_id'];
                    $meals =  $items['subscription'][0]['total_meals'];
                    $days =  (int) $items['subscription'][0]['days'];
                    $existing = Usersubscription_model::where('user_id', $userId)->first();
                    $data = [];
                    $startDate = Carbon::now('Asia/Kolkata');

                    if ($existing) {
                        $endDate = Carbon::now('Asia/Kolkata')->addDays($days);
                        $data = [
                            'user_id'        => $userId,
                            'plan_id'         => $planId,
                            'meals_remaining' => $meals,
                            'start_date'     => $startDate,
                            'end_date'       => ($days) ? $endDate : null,
                            'status' => 'active',
                        ];

                        $today = Carbon::today();
                        // check if plan is still active
                        if ($endDate->gte($today)  && $existing->meals_remaining > 0) {
                            return response()->json([
                                'success' => false,
                                'loggedin' => true,
                                'message' => 'You already have an active plan. You cannot purchase another one right now.'
                            ], 200);
                        }
                        $existing->update($data);
                        $msg = "Subscription details updated successfully.!";
                    } else {
                        $endDate = Carbon::now('Asia/Kolkata')->addDays($days);
                        $data = [
                            'user_id'        => $userId,
                            'plan_id'         => $planId,
                            'meals_remaining' => $meals,
                            'start_date'     => $startDate,
                            'end_date'       => ($days) ? $endDate : null,
                            'status' => 'active'
                        ];
                        Usersubscription_model::create($data);
                        $msg = "Subscription created successfully!";
                    }

                    return response()->json([
                        'success' => true,
                        'loggedin' => true,
                        'message' => $msg,
                        'data'    => $data,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'User logout can you please add '
                    ]);
                }
            } else {
                if (isset($items['subscription'])) {
                    if ($request->session()->has('user_id')) {
                        $userId = session('user_id');
                        $existing = Usersubscription_model::where('user_id', $userId)->first();
                        $data = [];
                        $startDate = Carbon::now('Asia/Kolkata');

                        if ($existing) {
                            $planId = $items['subscription'][0]['db_id'];
                            $meals =  $items['subscription'][0]['total_meals'];
                            $days =  $items['subscription'][0]['days'];

                            $endDate = Carbon::parse($existing->end_date);
                            $data = [
                                'user_id'        => $userId,
                                'plan_id'         => $planId,
                                'meals_remaining' => $meals,
                                'start_date'     => $startDate,
                                'end_date'       => ($days) ? $endDate : null,
                                'status' => 'active'
                            ];

                            $today = Carbon::today();
                            // check if plan is still active
                            if ($endDate->gte($today)  && $existing->meals_remaining > 0) {
                                return response()->json([
                                    'success' => false,
                                    'loggedin' => true,
                                    'message' => 'You already have an active plan. You cannot purchase another one right now.'
                                ], 200);
                            }
                            $existing->update($data);
                            $msg = "Subscription details updated successfully.!";
                        } else {
                            if (isset($items['subscription'])) {
                                $planId = $items['subscription'][0]['db_id'];
                                $meals =  $items['subscription'][0]['total_meals'];
                                $days = (int)  $items['subscription'][0]['days'];
                                // Ensure Asia/Kolkata timezone
                                $startDate = Carbon::now('Asia/Kolkata');
                                if ($days) {
                                    $endDate   = Carbon::now('Asia/Kolkata')->addDays($days);
                                }
                                $data = [
                                    'user_id'        => $userId,
                                    'plan_id'         => $planId,
                                    'meals_remaining' => $meals,
                                    'start_date'     => $startDate,
                                    'end_date'       => ($days) ? $endDate : null,
                                    'status' => 'active',
                                ];
                                Usersubscription_model::create($data);
                                $msg = "Subscription created successfully!";
                            }
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'User logout can you please add '
                        ]);
                    }
                }
            }
            if (isset($items['daywise'])) {
                $paymentStatus = 'Unpaid';
                $alacarteHtml = '<div style="max-width:600px; margin:20px auto; font-family:Arial, sans-serif; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">';

                foreach ($items['daywise'] as $item) {
                    $subscriptionPlanAmount = 0;
                    $subscription_plan = Usersubscription_model::where('user_id', session('user_id'))
                        ->first();
                    $total = $item['price'];
                    $quantityAfterPlan = $item['quantity'];
                    $totalDbMeal = 0;
                    $totalSubscriptionAmount = 0;
                    if ($subscription_plan && $item['is_deductamount'] == 'true') {
                        $now = Carbon::now('Asia/Kolkata');

                        // Check if end_date is greater than current date
                        if (Carbon::parse($subscription_plan->end_date, 'Asia/Kolkata')->greaterThan($now)) {
                            // Decrement meals_remaining if more than 0
                            if ($subscription_plan->meals_remaining > 0) {
                                $totalDbMeal = $subscription_plan->meals_remaining;
                                $totalMeal = $subscription_plan->meals_remaining - $item['quantity'];

                                if ($totalMeal < 0) {
                                    $paymentStatus = 'Partially Paid';
                                    $finalQty            = abs($totalMeal);
                                    $quantityAfterPlan   = $finalQty;                 // meals to charge
                                    $finalPrice          = $item['price'] * $finalQty;
                                    $total = $finalPrice;
                                } else {
                                    $quantityAfterPlan   = 0;
                                    $total               = 0;
                                }

                                $subscription_plan->decrement('meals_remaining', min($item['quantity'], $subscription_plan->meals_remaining));

                                // Optional: if meals_remaining becomes 0, mark expired
                                if ($subscription_plan->fresh()->meals_remaining <= 0) {
                                    $subscription_plan->status = 'expired';
                                    $subscription_plan->save();
                                } else {
                                    $paymentStatus = 'Paid';
                                }
                            } else {
                                $quantityAfterPlan = $item['quantity'];    // pay for all
                                $total             = $item['price'] * $item['quantity'];
                            }
                        } else {
                            $subscription_plan->status = 'expired';
                            $subscription_plan->save();
                            $quantityAfterPlan = $item['quantity'];
                            $total             = $item['price'] * $item['quantity'];
                        }
                    }
                    $orderId = $this->generateOrderId();
                    $orderDate = Carbon::parse($item['order_date'], 'Asia/Kolkata');
                    $now = Carbon::now('Asia/Kolkata');
                    if ($orderDate->greaterThan($now)) {
                        $alacarteHtml .= '
                        <div style="background:#4caf50; padding:20px; text-align:center;">
                            <h2 style="margin:0;">🧾 Your Order Summary</h2>
                        </div>
                        ';
                        $alacarteHtml .= '<div style="margin-bottom:5px border-bottom:1px solid #eee;">
                            <p style="margin:0; font-size:14px;">Date: <strong>' . $item['order_date'] . '</strong></p>
                            <p style="margin:0; font-size:14px;">Order ID: <strong>' . $orderId . '</strong></p>
                        </div>';
                        $added_data = [
                            'user_id' => session('user_id'),
                            'address_id' => $request->input('address_id'),
                            'order_type' => 'daywise',
                            'order_date' => $item['order_date'],
                            'order_id' => $orderId,
                            'payment_status' => $paymentStatus,
                            'order_status' => 'Online'
                        ];

                        $orderinsertedid = Customerorder_model::create($added_data);
                        if ($item['is_deductamount'] == 'true' && $subscription_plan && ($paymentStatus == 'Paid' || $paymentStatus == 'Partially Paid')) {
                            $added_data = [
                                'user_subscription_id' => $subscription_plan->id,
                                'order_id' => $orderinsertedid->id,
                                'meal_used' => ($totalDbMeal - $subscription_plan->meals_remaining)
                            ];

                            Subscription_meal_log_model::create($added_data);
                        }
                        $item_data = [
                            'order_id' => $orderinsertedid->id,
                            'item_type' => $item['type'],
                            'item_id' => $item['db_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['price'],
                            'total_price' => $item['price'] * $quantityAfterPlan,
                        ];
                        $daywiseItem = Daywisemenu_model::find($item['db_id']);
                        $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                        $alacarteHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $daywiseItem->title . '</strong> X ' . $item['quantity'] . '</div>';
                        $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . $item['price'] * $item['quantity'] . '</div>';
                        $alacarteHtml .= '</div>';
                        Customerorderitem_model::create($item_data);
                        $additionalItemTotal = 0;
                        if (isset($item['additional_items'])) {

                            foreach ($item['additional_items'] as $adt) {
                                $additionalItem = Additionalmenu_model::find($adt['id']);

                                $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                                $alacarteHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $additionalItem->name . '</strong> X ' . $adt['quantity'] . '</div>';
                                $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . $adt['price'] * $adt['quantity'] . '</div>';
                                $alacarteHtml .= '</div>';


                                $additionalItemTotal = $additionalItemTotal + ($adt['price'] * $adt['quantity']);
                                $item_data = [
                                    'order_id' => $orderinsertedid->id,
                                    'item_type' => $adt['type'],
                                    'item_id' => $adt['id'],
                                    'quantity' => $adt['quantity'],
                                    'unit_price' => $adt['price'],
                                    'total_price' => $adt['price'] * $adt['quantity'],
                                ];
                                Customerorderitem_model::create($item_data);
                            }
                        }
                        if ($subscription_plan && $item['is_deductamount'] == 'true') {
                            if ($subscription_plan->payment_status == 'Pending') {
                                $plan_details = Subscription_model::where('id', $subscription_plan->plan_id)
                                    ->first();
                                if ($plan_details) {
                                    $sub_data = [
                                        'order_id' => $orderinsertedid->id,
                                        'item_type' => 'subscription',
                                        'item_id' => $subscription_plan->plan_id,
                                        'quantity' => 1,
                                        'unit_price' => $plan_details->price,
                                        'total_price' => $plan_details->price,
                                    ];
                                    Customerorderitem_model::create($sub_data);
                                    $subscription_plan->update(['payment_status' => 'Unpaid']);
                                    $subscriptionPlanAmount = $plan_details->price;
                                }
                            }
                        }
                        $total = (float) $total + (float) $subscriptionPlanAmount;
                        $updated_data = [
                            'total_amount' => ($total * ($quantityAfterPlan == 0 ? 1 : $quantityAfterPlan) + $additionalItemTotal)
                                * ($total + $additionalItemTotal != 0 ? 1.25 : 1),
                        ];
                        $finalUSDAmount = ($total * ($quantityAfterPlan == 0 ? 1 : $quantityAfterPlan) + $additionalItemTotal)
                            * ($total + $additionalItemTotal != 0 ? 1.25 : 1);
                        $totalAmountForEmail=($total * ($quantityAfterPlan == 0 ? 1 : $quantityAfterPlan) + $additionalItemTotal);
                        $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden; border-top:1px solid #eee;">';
                        $alacarteHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>Total</strong></div>';
                        $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;">' . ($totalAmountForEmail) . '</div>';
                        $alacarteHtml .= '</div>';
                        $addQuarantine_failed = Customerorder_model::updateOrCreate(
                            ['id' => $orderinsertedid->id],
                            $updated_data
                        );
                       
                        // $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                        //     ->asForm()
                        //     ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                        //         'To' => '+91' . session('user_phone'),
                        //         'From' => env('TWILIO_PHONE'),
                        //         'Body' => 'Your tiffin order ' . $orderId . ' for ' . $item['order_date'] . ' has been confirmed.'
                        //     ]);
                        if (session('user_email')) {
                            $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                            $alacarteHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>USD 0.8</strong></div>';
                            $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;"><strong>' . $finalUSDAmount . '</strong></div>';
                            $alacarteHtml .= '</div></div>';
                            $data = [
                                'customerName' => session('user_name'),
                                'orderId' => $orderId,
                                'orderDate' => $item['order_date'],
                                'alacarteHtml' => $alacarteHtml
                            ];
                            Mail::send('email.confirmation', $data, function ($message) use ($data) {
                                $message->to(session('user_email'))
                                    ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                    ->subject('Desi Kitchen - Order Confirmation');
                            });
                        }

                        // $data = $response->json();

                        // if ($response->successful() && isset($data['status'])) {
                        //     // 'queued', 'sent', 'delivered' = success stages
                        //     if (in_array($data['status'], ['queued', 'sent', 'delivered'])) {
                        //         echo "Message sent successfully! SID: " . $data['sid'];
                        //     } else {
                        //         echo "Message failed with status: " . $data['status'];
                        //     }
                        // } else {
                        //     echo "Error sending message: " . $response->body();
                        // }

                    } else {
                        return response()->json([
                            "success" => false,
                            'message' => "Please select a future date for your order. than current date"
                        ], 200);
                    }
                }
            }
            if (isset($items['alacarte']) && !empty($items['alacarte'])) {
                $orderId = $this->generateOrderId();
                $alacarteHtml = '<div style="max-width:600px; margin:20px auto; font-family:Arial, sans-serif; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">';
                $added_data = [
                    'user_id' => session('user_id'),
                    'address_id' => $request->input('address_id'),
                    'order_type' => 'alacarte',
                    'order_date' => $request->input('alacarteorder_date'),
                    'order_id' => $orderId,
                    'order_status' => 'Online'
                ];
                $orderalacarteinsertedid = Customerorder_model::create($added_data);
                $alacarteItemTotal = 0;
                $additionalItemTotal = 0;
                $alacarteHtml .= `
                <div style="background:#4caf50; padding:20px; text-align:center;">
                    <h2 style="margin:0;">🧾 Your Order Summary</h2>
                </div>
                `;
                $alacarteHtml .= '<div style="margin-bottom:5px border-bottom:1px solid #eee;">
                    <p style="margin:0; font-size:14px;">Date: <strong>' . $request->input('alacarteorder_date') . '</strong></p>
                    <p style="margin:0; font-size:14px;">Order ID: <strong>' . $orderId . '</strong></p>
                </div>';
                foreach ($items['alacarte'] as $item) {
                    $alacarteItem = Alacartemenu_model::find($item['db_id']);
                    $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                    $alacarteHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $alacarteItem->name . '</strong> X ' . $item['quantity'] . '</div>';
                    $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . $item['price'] * $item['quantity'] . '</div>';
                    $alacarteHtml .= '</div>';

                    $item_data = [
                        'order_id' => $orderalacarteinsertedid->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['db_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ];
                    Customerorderitem_model::create($item_data);
                    if (isset($item['additional_items'])) {

                        foreach ($item['additional_items'] as $adt) {
                            $additionalItem = Additionalmenu_model::find($adt['id']);

                            $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                            $alacarteHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $additionalItem->name . '</strong> X ' . $adt['quantity'] . '</div>';
                            $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . $adt['price'] * $adt['quantity'] . '</div>';
                            $alacarteHtml .= '</div>';

                            $additionalItemTotal = $additionalItemTotal + ($adt['price'] * $adt['quantity']);
                            $item_data = [
                                'order_id' => $orderalacarteinsertedid->id,
                                'item_type' => $adt['type'],
                                'item_id' => $adt['id'],
                                'quantity' => $adt['quantity'],
                                'unit_price' => $adt['price'],
                                'total_price' => $adt['price'] * $adt['quantity'],
                            ];
                            Customerorderitem_model::create($item_data);
                        }
                    }
                    $alacarteItemTotal += ($item['price'] * $item['quantity']);
                }
                $updated_data = [
                    'total_amount' => $additionalItemTotal + $alacarteItemTotal,
                ];
                $updated_data = [
                    'total_amount' => ($alacarteItemTotal + $additionalItemTotal)
                        * ($alacarteItemTotal + $additionalItemTotal != 0 ? 1.25 : 1),
                ];
                $finalUSDAmount = ($alacarteItemTotal + $additionalItemTotal)
                    * ($alacarteItemTotal + $additionalItemTotal != 0 ? 1.25 : 1);
                $totalAmountForEmail=($alacarteItemTotal + $additionalItemTotal);
                $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden; border-top:1px solid #eee;">';
                $alacarteHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>Total</strong></div>';
                $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;">' . ($totalAmountForEmail) . '</div>';
                $alacarteHtml .= '</div>';
                $addQuarantine_failed = Customerorder_model::updateOrCreate(
                    ['id' => $orderalacarteinsertedid->id],
                    $updated_data
                );

                // $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                //     ->asForm()
                //     ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                //         'To' => '+91' . session('user_phone'),
                //         'From' => env('TWILIO_PHONE'),
                //         'Body' => 'Your alacarte order ' . $orderId . ' for ' .  $request->input('alacarteorder_date') . ' has been confirmed.'
                //     ]);
                if (session('user_email')) {
                    $alacarteHtml .= '<div style="margin-top:5px; margin-bottom:5px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                    $alacarteHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>USD 0.8</strong></div>';
                    $alacarteHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;"><strong>' . ($finalUSDAmount) . '</strong></div>';
                    $alacarteHtml .= '</div></div>';

                    $data = [
                        'customerName' => session('user_name'),
                        'orderId' => $orderId,
                        'orderDate' =>  $request->input('alacarteorder_date'),
                        'alacarteHtml' => $alacarteHtml
                    ];
                    Mail::send('email.confirmation', $data, function ($message) use ($data) {
                        $message->to(session('user_email'))
                            ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                            ->subject('Desi Kitchen - Order Confirmation');
                    });
                }
            }
            return response()->json([
                "success" => true,
                'message' => "Your order has been successfully placed !"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
            ], 200);
        }
        // Handle failed request (4xx or 5xx status codes)
        return [];
    }

    public function cancelOrder(Request $request)
    {
        try {
            $existing = Customerorder_model::find($request->input('order_id'));

            if (! $existing) {
                return response()->json([
                    "success" => false,
                    "message" => "Order not found"
                ], 200);
            }

            $tz = config('app.timezone') ?: 'UTC';
            // Parse order_date as a date (startOfDay ensures date-only)
            $orderDate = Carbon::parse($existing->order_date, $tz)->startOfDay();
            $now = Carbon::now($tz);
            $cutoffToday = Carbon::today($tz)->setTime(21, 0, 0);
            if ($orderDate->isTomorrow() && $now->greaterThan($cutoffToday)) {
                return response()->json([
                    "success" => false,
                    "message" => "You cannot cancel this order after 9 PM."
                ], 200);
            }
            // If order is for today -> also possibly block after today's 9 PM
            if ($orderDate->isToday() && $now->greaterThan($cutoffToday)) {
                return response()->json([
                    "success" => false,
                    "message" => "You cannot cancel today's order after 9 PM."
                ], 200);
            }

            $existing->delete();

            return response()->json([
                "success" => true,
                "message" => "Order cancelled successfully"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "error"   => $e->getMessage()
            ], 200);
        }
    }

    function generateOrderId()
    {
        // Get current year
        $year = date('Y');

        // Find the last order number for the current year
        $lastOrder = DB::table('orders')
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            // Extract last sequence number
            $lastNumber = (int) substr($lastOrder->order_id, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        // Format: ORD-YYYY-XXX
        return "ORD-{$year}-{$newNumber}";
    }
}

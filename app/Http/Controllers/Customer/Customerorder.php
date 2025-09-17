<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Additionalmenu_model;
use App\Models\Customer\Alacartemenu_model;
use App\Models\Customer\Customerorder_model;
use App\Models\Customer\Customerorderitem_model;
use App\Models\Customer\Daywisemenu_model;
use App\Models\Customer\Usersubscription_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Customerorder extends Controller
{
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
    public function index()
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
                        $menu = Daywisemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $unit = 'Qty';
                            $total_amount = $price * $item->quantity;
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
                'status'        => ucfirst($order->status),
                'total_amount'  => number_format($order->total_amount, 2),
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
                'current_date' => Carbon::today('Asia/Kolkata')->format('d-m-Y'),
                'delivered_at' => Carbon::parse($order->updated_at)->format('d-m-Y H:i'),
            ];
        });
        // print_r($data);
        // exit;

        return view('customer.order', ['orders' => $data]); // View: resources/views/admin/dashboard.blade.php
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
                if (isset($items['daywise'])) {
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
                $subscription_plan = Usersubscription_model::where('user_id', session('user_id'))
                    ->first();
                if ($subscription_plan) {
                    $now = Carbon::now('Asia/Kolkata');

                    // Check if end_date is greater than current date
                    if (Carbon::parse($subscription_plan->end_date, 'Asia/Kolkata')->greaterThan($now)) {
                        // Decrement meals_remaining if more than 0
                        if ($subscription_plan->meals_remaining > 0) {
                            $subscription_plan->decrement('meals_remaining', 1);
                            $paymentStatus = 'Paid';
                            // Optional: if meals_remaining becomes 0, mark expired
                            if ($subscription_plan->meals_remaining - 1 <= 0) {
                                $subscription_plan->status = 'expired';
                                $subscription_plan->save();
                            }
                        }
                    } else {
                        // Expired by date
                        $subscription_plan->status = 'expired';
                        $subscription_plan->save();
                    }
                }

                foreach ($items['daywise'] as $item) {
                    $orderId = $this->generateOrderId();
                    $orderDate = Carbon::parse($item['order_date'], 'Asia/Kolkata');
                    $now = Carbon::now('Asia/Kolkata');
                    if ($orderDate->greaterThan($now)) {

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

                        $item_data = [
                            'order_id' => $orderinsertedid->id,
                            'item_type' => $item['type'],
                            'item_id' => $item['db_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['price'],
                            'total_price' => $item['price'] * $item['quantity'],
                        ];
                        Customerorderitem_model::create($item_data);
                        $additionalItemTotal = 0;
                        if (isset($item['additional_items'])) {

                            foreach ($item['additional_items'] as $adt) {
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
                        $updated_data = [
                            'total_amount' => $additionalItemTotal + ($item['price'] * $item['quantity']),
                        ];
                        $addQuarantine_failed = Customerorder_model::updateOrCreate(
                            ['id' => $orderinsertedid->id],
                            $updated_data
                        );
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

                $added_data = [
                    'user_id' => session('user_id'),
                    'address_id' => $request->input('address_id'),
                    'order_type' => 'alacarte',
                    'order_date' => $items['alacarte'][0]['order_date'],
                    'order_id' => $orderId,
                    'order_status' => 'Online'
                ];

                $orderalacarteinsertedid = Customerorder_model::create($added_data);
                $alacarteItemTotal = 0;
                $additionalItemTotal = 0;
                foreach ($items['alacarte'] as $item) {


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
                $addQuarantine_failed = Customerorder_model::updateOrCreate(
                    ['id' => $orderalacarteinsertedid->id],
                    $updated_data
                );
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\Diningmenu_model;
use App\Models\Admin\KitUser;
use App\Models\Admin\Order_model;
use App\Models\Admin\Orderitem_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Admin\Subscription_model;
use App\Models\Admin\Useraddress_model;
use App\Models\Customer\Table_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Order extends Controller
{
    public function index()
    {
        return view('admin.order'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getAllMenuList()
    {
        $data = [];
        $today = Carbon::today(); // Get today's date

        $daywisemenu = Daywisemenu_model::where('menu_date', '>=', $today)->where('is_active', true)
            ->orderBy('menu_date', 'asc')
            ->get();
        $data['daywise'] = $daywisemenu;
        $alacartemenu = Category_model::with(['alacartemenus' => function ($query) {
            $query->where('is_active', true);
        }])->get();
        $data['alacarte'] = $alacartemenu;
        $partymenu = Partyemenu_model::where('is_active', '=', true)
            // ->orderBy('menu_date', 'asc')
            ->get();
        $data['party'] = $partymenu;
        $diningmenu = Category_model::with(['diningmenus' => function ($query) {
            $query->where('is_active', true);
        }])->get();
        $data['dining'] = $diningmenu;
        $additionalmenu = Additionalmenu_model::where('is_active', '=', true)
            // ->orderBy('menu_date', 'asc')
            ->get();
        $data['additional_menu'] = $additionalmenu;
        return response()->json([
            "success" => true,
            "data" => $data
        ]); // Return as JSON
    }


    public function getOrderListData(Request $request)
    {


        $query = Order_model::with(['user', 'items', 'address'])->orderBy('id', 'desc');
        if ($request->filter === 'today') {
            $query->whereDate('order_date', Carbon::today());
        } elseif ($request->filter == 'weekly') {
            $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->filter == 'monthly') {
            $query->whereMonth('order_date', Carbon::now()->month);
        } elseif ($request->filter == 'custom' && $request->fromDate && $request->toDate) {
            $query->whereBetween('order_date', [$request->fromDate, $request->toDate]);
        }

        $orders = $query->get();


        $data = $orders->map(function ($order) {
            $itemHtmlList = '<ul class="list-unstyled mb-0">';
            // echo "<pre>";
            // print_r($order->toArray());
            // echo "</pre>";
            // exit;
            foreach ($order->items as $item) {
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

                    case 'party':
                        $menu = Partyemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = ($menu->price_per_kg) ? $menu->price_per_kg : $menu->price_per_qty;
                            $unit = ($menu->price_per_kg) ? 'Kg' : 'Qty';
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
                        <li style="margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <strong>' . e($menuTitle) . '</strong><br>
                                    <small>Price: $' . number_format($price, 2) . ' x ' . $unit . ': ' . $item->quantity . '</small>
                                </div>
                                <div style="font-weight: bold;">$' . number_format($total_amount, 2) . '</div>
                            </div>
                        </li>';
                }
            }
            $itemHtmlList .= '</ul>';

            return [
                'id'            => $order->id,
                'customer'      => $order->user,
                'address' => $order->address
                    ? "{$order->address->label}<br>{$order->address->address_line1}, {$order->address->address_line2}<br>{$order->address->city} - {$order->address->pincode}"
                    : 'N/A',
                'items'         => $itemHtmlList,
                'order_type'    => ucfirst($order->order_type),
                'order_date'    =>  Carbon::parse($order->order_date)->format('d-m-Y'),
                'status'        => ucfirst($order->status),
                'status_badge'  => $this->getStatusBadge($order->status),
                'total_amount'  => number_format($order->total_amount, 2),
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json([
            'data' => $data,
        ], 200);
    }
    public function getTodayOrderListData(Request $request)
    {
        $today = Carbon::today('Asia/Kolkata')->format('Y-m-d');
        $query = Order_model::with(['user', 'items', 'address'])->orderBy('id', 'desc')
            ->when(Auth::user()->role == 'admin', function ($query) {
                // If role = admin, add condition
                return $query->where('created_by', Auth::user()->id);
            });
        if ($request->filter === 'today') {
            $query->whereDate('order_date', Carbon::today());
        } elseif ($request->filter == 'weekly') {
            $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($request->filter == 'monthly') {
            $query->whereMonth('order_date', Carbon::now()->month);
        } elseif ($request->filter == 'custom' && $request->fromDate && $request->toDate) {
            $query->whereBetween('order_date', [$request->fromDate, $request->toDate]);
        }

        $orders = $query->get();
        $data = $orders->map(function ($order) {
            $itemHtmlList = '<ul class="list-unstyled mb-0">';
            // echo "<pre>";
            // print_r($order->toArray());
            // echo "</pre>";
            // exit;
            foreach ($order->items as $item) {
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

                    case 'party':
                        $menu = Partyemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = ($menu->price_per_kg) ? $menu->price_per_kg : $menu->price_per_qty;
                            $unit = ($menu->price_per_kg) ? 'Kg' : 'Qty';
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
                    case 'dining':
                        $menu = Diningmenu_model::find($item->item_id);
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
                            $unit = '';
                            $total_amount = $item->total_price;
                            $menuTitle = "Meal " . $sub->name;
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
                    $smallText = '';
                    if (!empty($unit)) {
                        $smallText = '<small>' . e($unit) . ': ' . e($item->quantity) . '</small>';
                    }
                    $itemHtmlList .= '
                        <li style="margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <strong>' . e($menuTitle) . '</strong><br>
                                    ' . $smallText . '
                                </div>
                            </div>
                        </li>';
                }
            }
            $itemHtmlList .= '</ul>';
            $statuses = ['pending', 'confirmed', 'outfordelivery', 'delivered'];
            $currentStatus = $order->status;
            $currentIndex = array_search($currentStatus, $statuses);

            $nextStatus = null;

            if ($currentIndex !== false && $currentIndex < count($statuses) - 1) {
                $nextStatus = $statuses[$currentIndex + 1];
            }
            $rate = 0.25;
            $base = ($order->total_amount / (1 + $rate));

            return [
                'id'            => $order->id,
                'customer'      => $order->user,
                'address' => $order->address
                    ? "{$order->address->label}<br>{$order->address->address_line1}, {$order->address->address_line2}<br>{$order->address->city} - {$order->address->pincode}<br>"
                    : 'Table Order',
                'items'         => $itemHtmlList,
                'order_id' => $order->order_id,
                'order_type'    => ucfirst($order->order_type),
                'order_status'    => ucfirst($order->order_status),
                'order_date'    =>  Carbon::parse($order->order_date)->format('d-m-Y'),
                'status'        => ucfirst($order->status),
                'next_status' => $nextStatus,
                'status_cap' => $this->getStatusName($order->status),
                'next_btn_status' => ($nextStatus) ? $this->getStatusButton($nextStatus) : '',
                'status_badge'  => $this->getStatusBadge($order->status),
                // 'total_amount'  => number_format($order->total_amount, 2),
                'total_amount' => number_format($base, 2),
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json([
            'data' => $data,
        ], 200);
    }

    private function getStatusBadge($status)
    {
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'outfordelivery'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>" . ucfirst($status) . "</span>";
    }
    private function getStatusButton($status)
    {
        $statusname = ['warning' => 'Warning', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'outfordelivery'  => 'Out For Delivery', 'delivered'   => 'Delivered'];
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'rejected' => 'danger', 'outfordelivery'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<button  class='btn btn-{$color} btn-chanage-status p-1'><span class='spinner-border spinner-border-sm me-2 d-none' role='status' aria-hidden='true'></span>" . ucfirst($statusname[$status]) . "</button>";
    }
    private function getStatusName($status)
    {
        $statusname = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'rejected' => 'Rejected', 'outfordelivery'  => 'Out For Delivery', 'delivered'   => 'Delivered'];
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'cancelled' => 'danger', 'outfordelivery'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>" . ucfirst($statusname[$status]) . "</span>";
    }


    public function deleteDayWiseMenu(Request $request)
    {
        $menu = Daywisemenu_model::find($request->user_uid); // Adjust model name

        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu not found']);
        }
        // Delete image if exists
        if ($request->has('image_path') && File::exists(public_path('storage/' . $request->image_path))) {
            File::delete(public_path('storage/' . $request->image_path));
        }

        // Or delete from storage if saved via Storage::put
        Storage::delete('public/' . $request->image_path);

        // Delete the menu record
        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu item removed successfully.']);
    }

    public function addUpdateOrder(Request $request)
    {
        try {
            if (empty($request->input('hid_orderid'))) {
                $added_data = [
                    'status' => $request->input('drp_status'),
                    'note' => $request->input('txt_note')
                ];

                Order_model::create($added_data);

                return response()->json([
                    "success" => true,
                    'message' => "Your order has been successfully placed !"
                ], 200);
            } else {
                $updated_data = [
                    'status' => $request->input('drp_status'),
                    'note' => $request->input('txt_note')
                ];
                Order_model::updateOrCreate(
                    ['id' => $request->input('hid_orderid')],
                    $updated_data
                );
                $existOrder = Order_model::find($request->input('hid_orderid'));
                $existCustomer = KitUser::find($existOrder->user_id);

            
                if ($request->input('drp_status') == 'pending') {
                    $orderHtml = '<div style="max-width:600px; margin:20px auto; font-family:Arial, sans-serif; background:#fff;border:1px solid #FFFFFF; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">';
                    $orderHtml .= '
                            <div style="background:#FEA116; color:#FFFFFF; padding:10px; text-align:center;">
                        <h2 style="margin:0;">🧾 Your Order Summary</h2>
                    </div>
                    ';
                    $orderHtml .= '<div style="margin-bottom:5px border-bottom:1px solid #eee;">
                        <p style="margin:0; font-size:14px;">Date: <strong>' . $existOrder->order_date . '</strong></p>
                        <p style="margin:0; font-size:14px;">Order ID: <strong>' . $existOrder->order_id . '</strong></p>
                    </div>';
                    $existOrderItem = Orderitem_model::where("order_id", $existOrder->id)->get();
                    $totalAmount = 0;
                    foreach ($existOrderItem as $orditem) {
                        $itemName = null;
                        if ($orditem['item_type'] == 'alacarte') {
                            $alacarteItem = Alacartemenu_model::find($orditem->item_id);
                            $itemName = $alacarteItem ? $alacarteItem->name : null;
                        }
                        if ($orditem['item_type'] == 'daywise') {
                            $daywiseItem = Daywisemenu_model::find($orditem->item_id);
                            $itemName = $daywiseItem ? $daywiseItem->title : null;
                        }
                        if ($orditem['item_type'] == 'additional') {
                            $additionalItem = Additionalmenu_model::find($orditem->item_id);
                            $itemName = $additionalItem ? $additionalItem->name : null;
                        }
                        $totalAmount += $orditem['unit_price'] * $orditem['quantity'];
                        $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                        $orderHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $itemName . '</strong> X ' . $orditem['quantity'] . '</div>';
                        $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . number_format($orditem['unit_price'] * $orditem['quantity'], 2) . '</div>';
                        $orderHtml .= '</div>';
                    }
                    
                    $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; border-bottom:1px solid #ddd; padding-bottom:8px; overflow:hidden;">';
                    $orderHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>Total</strong></div>';
                    $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;">' . number_format($totalAmount,2) . '</div>';
                    $orderHtml .= '</div>';
                    $finalUSDAmount = $totalAmount * ($totalAmount != 0 ? 1.25 : 1);
                    $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; padding-bottom:8px; overflow:hidden;">';
                    $orderHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>USD 0.8</strong></div>';
                    $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;"><strong>' . number_format($finalUSDAmount,2) . '</strong></div>';
                    $orderHtml .= '</div></div>';
                    $data = [
                        'customerName' => $existCustomer->name,
                        'orderId' => $existOrder->order_id,
                        'orderDate' => $existOrder->order_date,
                        'alacarteHtml' => $orderHtml
                    ];

                    if ($existCustomer->email) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'Your order ' . $existOrder->order_id . ' for ' .  $existOrder->order_date . ' is currently pending confirmation'
                            ]);

                        Mail::send('email.pending_confirmation', $data, function ($message) use ($data) {
                            $message->to(session('user_email'))
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Pending Confirmation');
                        });
                    }
                }
                if ($request->input('drp_status') == 'confirmed') {
                    $orderHtml = '<div style="max-width:600px; margin:20px auto; font-family:Arial, sans-serif; background:#fff;border:1px solid #FFFFFF; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">';
                    $orderHtml .= '
                            <div style="background:#FEA116; color:#FFFFFF; padding:10px; text-align:center;">
                        <h2 style="margin:0;">🧾 Your Order Summary</h2>
                    </div>
                    ';
                    $orderHtml .= '<div style="margin-bottom:5px border-bottom:1px solid #eee;">
                        <p style="margin:0; font-size:14px;">Date: <strong>' . $existOrder->order_date . '</strong></p>
                        <p style="margin:0; font-size:14px;">Order ID: <strong>' . $existOrder->order_id . '</strong></p>
                    </div>';
                    $existOrderItem = Orderitem_model::where("order_id", $existOrder->id)->get();
                    $totalAmount = 0;
                    foreach ($existOrderItem as $orditem) {
                        $itemName = null;
                        if ($orditem['item_type'] == 'alacarte') {
                            $alacarteItem = Alacartemenu_model::find($orditem->item_id);
                            $itemName = $alacarteItem ? $alacarteItem->name : null;
                        }
                        if ($orditem['item_type'] == 'daywise') {
                            $daywiseItem = Daywisemenu_model::find($orditem->item_id);
                            $itemName = $daywiseItem ? $daywiseItem->title : null;
                        }
                        if ($orditem['item_type'] == 'additional') {
                            $additionalItem = Additionalmenu_model::find($orditem->item_id);
                            $itemName = $additionalItem ? $additionalItem->name : null;
                        }
                        $totalAmount += $orditem['unit_price'] * $orditem['quantity'];
                        $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; border-bottom:1px dashed #ddd; padding-bottom:8px; overflow:hidden;">';
                        $orderHtml .= '<div style="float:left; width:70%; font-size:14px;"><strong>' . $itemName . '</strong> X ' . $orditem['quantity'] . '</div>';
                        $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:14px;">$' . number_format($orditem['unit_price'] * $orditem['quantity'], 2) . '</div>';
                        $orderHtml .= '</div>';
                    }
                    
                    $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; border-bottom:1px solid #ddd; padding-bottom:8px; overflow:hidden;">';
                    $orderHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>Total</strong></div>';
                    $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;">' . number_format($totalAmount,2) . '</div>';
                    $orderHtml .= '</div>';
                    $finalUSDAmount = $totalAmount * ($totalAmount != 0 ? 1.25 : 1);
                    $orderHtml .= '<div style="margin:5px; margin-left:10px; margin-right:10px; padding-bottom:8px; overflow:hidden;">';
                    $orderHtml .= '<div style="float:left; width:70%; font-size:15px;"><strong>USD 0.8</strong></div>';
                    $orderHtml .= '<div style="float:right; width:28%; text-align:right; font-size:15px;"><strong>' . number_format($finalUSDAmount,2) . '</strong></div>';
                    $orderHtml .= '</div></div>';
                    $data = [
                        'customerName' => $existCustomer->name,
                        'email' => $existCustomer->email,
                        'orderId' => $existOrder->order_id,
                        'orderDate' => $existOrder->order_date,
                        'alacarteHtml' => $orderHtml,
                        'reason' => ($request->input('txt_note'))?'<p>Desikitchen Notes:  '.$request->input('txt_note').'</p>':"",
                    ];

                    if ($existCustomer->email) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'Your order ' . $existOrder->order_id . ' for ' .  $existOrder->order_date . ' has been confirmed.'
                            ]);
                        print_r($response->json('message'));
                        Mail::send('email.confirmation', $data, function ($message) use ($data) {
                            $message->to($data['email'])
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Confirmation');
                        });
                    }
                }
                if ($request->input('drp_status') == 'outfordelivery') {
                    $data = [
                        'customerName' => $existCustomer->name,
                        'orderId' => $existOrder->order_id,
                        'email' => $existCustomer->email,
                        'reason' => ($request->input('txt_note'))?'<p>Desikitchen Notes:  '.$request->input('txt_note').'</p>':"",
                    ];
                    if ($existCustomer->email) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'Your order ' . $existOrder->order_id . ' is out for delivery'
                            ]);
                        Mail::send('email.delivery_notification', $data, function ($message) use ($data) {
                            $message->to($data['email'])
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Out For Delivery');
                        });
                    }
                }
                if ($request->input('drp_status') == 'delivered') {
                    $data = [
                        'customerName' => $existCustomer->name,
                        'orderId' => $existOrder->order_id,
                        'orderDate' => $existOrder->order_date,
                        'email' => $existCustomer->email,
                        'reason' => ($request->input('txt_note'))?'<p>Desikitchen Notes:  '.$request->input('txt_note').'</p>':"",
                    ];
                    if ($existCustomer->email) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'We are happy to inform you that your Desi Kitchen Order #' . $existOrder->order_id . ' for ' . $existOrder->order_date . ' has been successfully delivered.'
                            ]);
                        Mail::send('email.order_delivered', $data, function ($message) use ($data) {
                            $message->to($data['email'])
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Delivered');
                        });
                    }
                }
                if ($request->input('drp_status') == 'cancelled') {
                    $data = [
                        'customerName' => $existCustomer->name,
                        'orderId' => $existOrder->order_id,
                        'orderDate' => $existOrder->order_date,
                        'email' => $existCustomer->email,
                        'reason' => ($request->input('txt_note'))?'<p>Desikitchen Notes:  '.$request->input('txt_note').'</p>':"",
                    ];
                    if ($existCustomer->email) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'We’re sorry to inform you that your Order #' . $existOrder->order_id . ' Placed for ' . $existOrder->order_date . ' has been cancelled.'
                            ]);
                        Mail::send('email.admin_cancel_order', $data, function ($message) use ($data) {
                            $message->to($data['email'])
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Cancelled');
                        });
                    }
                    // $existOrder->delete();
                }

                return response()->json([
                    "success" => true,
                    'message' => "Your order details have been updated successfully."
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

    public function addOrder(Request $request)
    {
        try {
            $items = $request->input('cart');
            if (isset($items['daywise'])) {
                $daywiseHtml = '';
                foreach ($items['daywise'] as $item) {
                    $daywiseItem = Daywisemenu_model::find($item['db_id']);

                    $daywiseHtml .= '<p><b>Date : ' . $item['order_date'] . '</b></p>';
                    $order_id = $this->generateOrderId();
                    $daywiseHtml .= '<div class="item-title">';
                    $daywiseHtml .= '<div>' . $daywiseItem->name . '</div>';
                    $daywiseHtml .= '<div>' . $item['price'] * $item['quantity'] . '</div>';
                    $daywiseHtml .= '</div>';
                    $added_data = [
                        'user_id' => $request->input('user_id'),
                        'address_id' => $request->input('address_id'),
                        'order_type' => 'daywise',
                        'order_date' => $item['order_date'],
                        'order_id' => $order_id,
                        'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
                        'order_status' => 'Cash',
                        'status'=> 'confirmed'
                    ];

                    $orderinsertedid = Order_model::create($added_data);

                    $item_data = [
                        'order_id' => $orderinsertedid->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['db_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ];
                    Orderitem_model::create($item_data);
                    $additionalItemTotal = 0;
                    if (isset($item['additional_items'])) {

                        foreach ($item['additional_items'] as $adt) {
                            $additionalItem = Additionalmenu_model::find($adt['id']);
                            $additionalItemTotal = $additionalItemTotal + ($item['price'] * $item['quantity']);
                            $daywiseHtml .= '<div class="item-title">';
                            $daywiseHtml .= '<div>' . $additionalItem->name . '</div>';
                            $daywiseHtml .= '<div>' . $adt['price'] * $adt['quantity'] . '</div>';
                            $daywiseHtml .= '</div>';

                            $item_data = [
                                'order_id' => $orderinsertedid->id,
                                'item_type' => $adt['type'],
                                'item_id' => $adt['id'],
                                'quantity' => $adt['quantity'],
                                'unit_price' => $adt['price'],
                                'total_price' => $adt['price'] * $adt['quantity'],
                            ];
                            Orderitem_model::create($item_data);
                        }
                    }
                    $updated_data = [
                        'total_amount' => $additionalItemTotal + ($item['price'] * $item['quantity']) * ($item['price'] != 0 ? 1.25 : 1),
                    ];
                    $daywiseHtml .= '<div class="item-title">';
                    $daywiseHtml .= '<div>Total</div>';
                    $daywiseHtml .= '<div>' . $additionalItemTotal + ($item['price'] * $item['quantity']) . '</div>';
                    $daywiseHtml .= '</div>';
                    $existCustomer = KitUser::find($request->input('user_id'));
                    if ($existCustomer) {
                        $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                            ->asForm()
                            ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                                'To' => $existCustomer->country_code . $existCustomer->phone,
                                'From' => env('TWILIO_PHONE'),
                                'Body' => 'Your tiffin order ' . $order_id . ' for ' . $item['order_date'] . ' has been confirmed.'
                            ]);
                        if (!empty($existCustomer->email)) {
                            $daywiseHtml .= '<div class="item-title">';
                            $daywiseHtml .= '<div>USD 0.8</div>';
                            $daywiseHtml .= '<div>' . $additionalItemTotal + ($item['price'] * $item['quantity']) * ($item['price'] != 0 ? 1.25 : 1) . '</div>';
                            $daywiseHtml .= '</div>';
                            $data = [
                                'customerName' => $existCustomer->name,
                                'orderId' => $order_id,
                                'orderDate' => $item['order_date'],
                                'email' => $existCustomer->email,
                                'alacarteHtml' => $daywiseHtml
                            ];
                            Mail::send('email.confirmation', $data, function ($message) use ($data) {
                                $message->to($data['email'])
                                    ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                    ->subject('Desi Kitchen - Order Confirmation');
                            });
                        }
                    }

                    $addQuarantine_failed = Order_model::updateOrCreate(
                        ['id' => $orderinsertedid->id],
                        $updated_data
                    );
                }
            }
            if (isset($items['alacarte']) && !empty($items['alacarte'])) {
                $order_id = $this->generateOrderId();
                $alacarteHtml = '';
                $added_data = [
                    'user_id' => $request->input('user_id'),
                    'address_id' => $request->input('address_id'),
                    'order_type' => 'alacarte',
                    'order_date' => $request->input('alacarteorder_date'),
                    'order_id' => $order_id,
                    'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
                    'order_status' => 'Cash',
                    'status'=> 'confirmed'
                ];

                $orderalacarteinsertedid = Order_model::create($added_data);
                $alacarteItemTotal = 0;
                $additionalItemTotal = 0;
                $alacarteHtml .= '<p><b>Date : ' . $request->input('alacarteorder_date') . '</b></p>';
                foreach ($items['alacarte'] as $item) {
                    $alacarteItem = Alacartemenu_model::find($item['db_id']);
                    $alacarteHtml .= '<div class="item-title">';
                    $alacarteHtml .= '<div>' . $alacarteItem->name . '</div>';
                    $alacarteHtml .= '<div>' . $item['price'] * $item['quantity'] . '</div>';
                    $alacarteHtml .= '</div>';
                    $item_data = [
                        'order_id' => $orderalacarteinsertedid->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['db_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ];
                    Orderitem_model::create($item_data);
                    if (isset($item['additional_items'])) {

                        foreach ($item['additional_items'] as $adt) {

                            $additionalItem = Additionalmenu_model::find($adt['id']);
                            $alacarteHtml .= '<div class="item-title">';
                            $alacarteHtml .= '<div>' . $additionalItem->name . '</div>';
                            $alacarteHtml .= '<div>' . $adt['price'] * $adt['quantity'] . '</div>';
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
                            Orderitem_model::create($item_data);
                        }
                    }
                    $alacarteItemTotal += ($item['price'] * $item['quantity']);
                }
                $updated_data = [
                    'total_amount' => ($additionalItemTotal + $alacarteItemTotal) * ($alacarteItemTotal != 0 ? 1.25 : 1),
                ];
                $alacarteHtml .= '<div class="item-title">';
                $alacarteHtml .= '<div>Total</div>';
                $alacarteHtml .= '<div>' . ($additionalItemTotal + $alacarteItemTotal) . '</div>';
                $alacarteHtml .= '</div>';

                $existCustomer = KitUser::find($request->input('user_id'));
                if ($existCustomer) {
                    $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                        ->asForm()
                        ->post("https://api.twilio.com/2010-04-01/Accounts/" . env('TWILIO_SID') . "/Messages.json", [
                            'To' => $existCustomer->country_code . $existCustomer->phone,
                            'From' => env('TWILIO_PHONE'),
                            'Body' => 'Your alacarte order ' . $order_id . ' for ' . $request->input('alacarteorder_date') . ' has been confirmed.'
                        ]);

                    if (!empty($existCustomer->email)) {
                        $alacarteHtml .= '<div class="item-title">';
                        $alacarteHtml .= '<div>USD 0.8</div>';
                        $alacarteHtml .= '<div>' . ($additionalItemTotal + $alacarteItemTotal) * ($alacarteItemTotal != 0 ? 1.25 : 1) . '</div>';
                        $alacarteHtml .= '</div>';

                        $data = [
                            'customerName' => $existCustomer->name,
                            'orderId' => $order_id,
                            'orderDate' => $request->input('alacarteorder_date'),
                            'email' => $existCustomer->email,
                            'alacarteHtml' => $alacarteHtml
                        ];
                        Mail::send('email.confirmation', $data, function ($message) use ($data) {
                            $message->to($data['email'])
                                ->from('info@desikitchen-ky.com', 'Desi Kitchen')
                                ->subject('Desi Kitchen - Order Confirmation');
                        });
                    }
                }



                $addQuarantine_failed = Order_model::updateOrCreate(
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

    public function selectedAddress(Request $request)
    {
        try {
            $userId = $request->input('user_id');
            $addressId = $request->input('address_id');
            Useraddress_model::where('user_id', $userId)
                ->update(['is_default' => false]);
            $existingAddress = Useraddress_model::find($addressId);
            if ($existingAddress) {
                // Update existing user
                $existingAddress->update([
                    'is_default'  => true,
                ]);
            }
            return response()->json([
                "success" => true,
                'message' => "Address selected successfully!"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'error' => $e->getMessage()
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


    public function addTableOrder(Request $request)
    {
        try {
            $existingUser = KitUser::where('phone', $request->input('txt_phone'))->first();
            $userId = '';
            if ($existingUser) {
                // Update existing user
                $existingUser->update([
                    'name'  => $request->input('txt_name'),
                    'email' => ($existingUser->email) ? $existingUser->email : $request->input('txt_email'),
                ]);

                $userId = $existingUser->id;  // store existing user id
            } else {
                if ($request->input('txt_email')) {
                    $existingEmailUser = KitUser::where('email', $request->input('txt_email'))->first();
                    if ($existingEmailUser) {
                        $existingEmailUser->update([
                            'name'  => $request->input('txt_name'),
                            'phone' => $request->input('txt_phone'),
                            'country_code' => $request->input('txt_code')
                        ]);
                    } else {
                        $newUser = KitUser::create([
                            'name'      => $request->input('txt_name'),
                            'email'     => $request->input('txt_email'),
                            'phone'     => $request->input('txt_phone'),
                            'country_code' => $request->input('txt_code'),
                            'role'      => 'user'
                        ]);

                        $userId = $newUser->id;
                    }
                } else {
                    // Insert new user
                    $newUser = KitUser::create([
                        'name'      => $request->input('txt_name'),
                        'email'     => $request->input('txt_email'),
                        'phone'     => $request->input('txt_phone'),
                        'country_code' => $request->input('txt_code'),
                        'role'      => 'user'
                    ]);

                    $userId = $newUser->id;  // store new user id
                }
            }
            $items = $request->input('cart');
            if (isset($items['daywise'])) {

                foreach ($items['daywise'] as $item) {
                    $order_id = $this->generateOrderId();
                    $added_data = [
                        'user_id' => $userId,
                        'address_id' => null,
                        'order_type' => 'daywise',
                        'order_date' => $item['order_date'],
                        'order_id' => $order_id,
                        'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
                        'status' => 'delivered',
                        'payment_status' => 'Paid',
                    ];

                    $orderinsertedid = Order_model::create($added_data);

                    $item_data = [
                        'order_id' => $orderinsertedid->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['db_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ];
                    Orderitem_model::create($item_data);
                    $additionalItemTotal = 0;
                    if (isset($item['additional_items'])) {

                        foreach ($item['additional_items'] as $adt) {
                            $additionalItemTotal = $additionalItemTotal + ($item['price'] * $item['quantity']);
                            $item_data = [
                                'order_id' => $orderinsertedid->id,
                                'item_type' => $item['type'],
                                'item_id' => $item['db_id'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['price'],
                                'total_price' => $item['price'] * $item['quantity'],
                            ];
                            Orderitem_model::create($item_data);
                        }
                    }
                    $updated_data = [
                        'total_amount' => $additionalItemTotal + ($item['price'] * $item['quantity']),
                    ];
                    $addQuarantine_failed = Order_model::updateOrCreate(
                        ['id' => $orderinsertedid->id],
                        $updated_data
                    );
                }
            }
            if (isset($items['dining']) && !empty($items['dining'])) {
                $order_id = $this->generateOrderId();

                $added_data = [
                    'user_id' => $userId,
                    'address_id' => null,
                    'order_type' => 'dining',
                    'order_date' => Carbon::now()->toDateString(),
                    'order_id' => $order_id,
                    'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
                    'status' => 'delivered',
                    'payment_status' => 'Paid',
                    'order_status' => $request->input('payment_type')
                ];

                $orderalacarteinsertedid = Order_model::create($added_data);
                $alacarteItemTotal = 0;
                $additionalItemTotal = 0;
                foreach ($items['dining'] as $item) {


                    $item_data = [
                        'order_id' => $orderalacarteinsertedid->id,
                        'item_type' => $item['type'],
                        'item_id' => $item['db_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                    ];
                    Orderitem_model::create($item_data);
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
                            Orderitem_model::create($item_data);
                        }
                    }
                    $alacarteItemTotal += ($item['price'] * $item['quantity']);
                }
                $updated_data = [
                    'total_amount' => ($additionalItemTotal + $alacarteItemTotal) * ($alacarteItemTotal != 0 ? 1.25 : 1),
                ];
                $addQuarantine_failed = Order_model::updateOrCreate(
                    ['id' => $orderalacarteinsertedid->id],
                    $updated_data
                );
            }
            $table = Table_model::find($request->input('table_id'));
            if (!$table) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table not found.'
                ]);
            }
            $table->user_id = null;
            $table->save();
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

    public function deleteOrder(Request $request)
    {
        try {
            $orderId = $request->input('order_id');

            // Check if order ID is provided
            if (!$orderId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID is required.'
                ], 400);
            }

            // Find the order
            $existOrder = Order_model::find($orderId);

            if (!$existOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            // Delete the order
            $existOrder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while deleting the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

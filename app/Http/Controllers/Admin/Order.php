<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Alacartemenu_model;
use App\Models\Admin\Category_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\KitUser;
use App\Models\Admin\Order_model;
use App\Models\Admin\Orderitem_model;
use App\Models\Admin\Partyemenu_model;
use App\Models\Customer\Table_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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

        $daywisemenu = Daywisemenu_model::where('menu_date', '>=', $today)
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
            $statuses = ['pending', 'confirmed', 'outfordelivery', 'delivered'];
            $currentStatus = $order->status;
            $currentIndex = array_search($currentStatus, $statuses);

            $nextStatus = null;

            if ($currentIndex !== false && $currentIndex < count($statuses) - 1) {
                $nextStatus = $statuses[$currentIndex + 1];
            }
            return [
                'id'            => $order->id,
                'customer'      => $order->user,
                'address' => $order->address
                    ? "{$order->address->label}<br>{$order->address->address_line1}, {$order->address->address_line2}<br>{$order->address->city} - {$order->address->pincode}<br>Phone: {$order->address->phone}"
                    : 'Table Order',
                'items'         => $itemHtmlList,
                'order_type'    => ucfirst($order->order_type),
                'order_date'    =>  Carbon::parse($order->order_date)->format('d-m-Y'),
                'status'        => ucfirst($order->status),
                'next_status' => $nextStatus,
                'next_btn_status' => ($nextStatus) ? $this->getStatusButton($nextStatus) : '',
                'status_badge'  => $this->getStatusBadge($order->status),
                'total_amount'  => number_format($order->total_amount, 2),
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json([
            'data' => $data,
        ], 200);
    }

    private function getStatusBadge($status)
    {
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'rejected' => 'danger', 'outfordelivery'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>" . ucfirst($status) . "</span>";
    }
    private function getStatusButton($status)
    {
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'rejected' => 'danger', 'outfordelivery'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<button  class='btn btn-{$color} btn-chanage-status p-1'>" . ucfirst($status) . "</button>";
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

        return response()->json(['success' => true, 'message' => 'Menu deleted successfully']);
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
                    'message' => "Order added successfully"
                ], 200);
            } else {
                $updated_data = [
                    'status' => $request->input('drp_status'),
                    'note' => $request->input('txt_note')
                ];
                $addQuarantine_failed = Order_model::updateOrCreate(
                    ['id' => $request->input('hid_orderid')],
                    $updated_data
                );
                return response()->json([
                    "success" => true,
                    'message' => "Order updated successfully"
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

                foreach ($items['daywise'] as $item) {
                    $order_id = $this->generateOrderId();
                    $added_data = [
                        'user_id' => $request->input('user_id'),
                        'address_id' => $request->input('address_id'),
                        'order_type' => 'daywise',
                        'order_date' => $item['order_date'],
                        'order_id' => $order_id,
                        'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
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
            if (isset($items['alacarte']) && !empty($items['alacarte'])) {
                $order_id = $this->generateOrderId();

                $added_data = [
                    'user_id' => $request->input('user_id'),
                    'address_id' => $request->input('address_id'),
                    'order_type' => 'alacarte',
                    'order_date' => $request->input('alacarteorder_date'),
                    'order_id' => $order_id,
                    'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null
                ];

                $orderalacarteinsertedid = Order_model::create($added_data);
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
                    'total_amount' => $additionalItemTotal + $alacarteItemTotal,
                ];
                $addQuarantine_failed = Order_model::updateOrCreate(
                    ['id' => $orderalacarteinsertedid->id],
                    $updated_data
                );
            }

            return response()->json([
                "success" => true,
                'message' => "Order added successfully"
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


    public function addTableOrder(Request $request)
    {
        try {
            $existingUser = KitUser::where('phone', $request->input('txt_phone'))->first();

            if ($existingUser) {
                // Update existing user
                $existingUser->update([
                    'name'  => $request->input('txt_name'),
                    'email' => $request->input('txt_email'),
                ]);

                $userId = $existingUser->id;  // store existing user id
            } else {
                // Insert new user
                $newUser = KitUser::create([
                    'name'      => $request->input('txt_name'),
                    'email'     => $request->input('txt_email'),
                    'phone'     => $request->input('txt_phone'),
                    'role'      => 'user'
                ]);

                $userId = $newUser->id;  // store new user id
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
                        'payment_status' => 'Paid'
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
            if (isset($items['alacarte']) && !empty($items['alacarte'])) {
                $order_id = $this->generateOrderId();

                $added_data = [
                    'user_id' => $userId,
                    'address_id' => null,
                    'order_type' => 'alacarte',
                    'order_date' => Carbon::now()->toDateString(),
                    'order_id' => $order_id,
                    'created_by' => (Auth::user()->role == 'admin') ? Auth::user()->id : null,
                    'status' => 'delivered',
                    'payment_status' => 'Paid'
                ];

                $orderalacarteinsertedid = Order_model::create($added_data);
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
                    'total_amount' => $additionalItemTotal + $alacarteItemTotal,
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
                'message' => "Order added successfully"
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
}

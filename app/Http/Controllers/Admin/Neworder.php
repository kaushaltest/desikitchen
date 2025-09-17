<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Additionalmenu_model;
use App\Models\Admin\Daywisemenu_model;
use App\Models\Admin\Order_model;
use App\Models\Admin\Partyemenu_model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Neworder extends Controller
{
    public function index()
    {
        return view('admin.neworder'); // View: resources/views/admin/dashboard.blade.php
    }
    public function getOrderListData()
    {
        $orders = Order_model::with(['user', 'items', 'address'])->orderBy('id', 'desc')->get();

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
                            $total_amount = $price * $item->quantity;
                            $menuTitle = $menu->title;
                        }
                        break;

                    case 'additional':
                        $menu = Additionalmenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $total_amount = $price * $item->quantity;
                            $menuTitle = $menu->name;
                        }
                        break;

                    case 'party':
                        $menu = Partyemenu_model::find($item->item_id);
                        if ($menu) {
                            $price = $menu->price;
                            $total_amount = $price * $item->quantity;
                            $menuTitle = $menu->name;
                        }
                        break;

                    case 'meal':
                        $menuTitle = "Meal Item #{$item->item_id}";
                        $price = 0;
                        $total_amount = 0;
                        break;

                    default:
                        $menuTitle = "Unknown Item #{$item->item_id}";
                        $price = 0;
                        $total_amount = 0;
                        break;
                }
                if (isset($menuTitle)) {
                    $itemHtmlList .= '
                        <li style="margin-bottom: 8px;">
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <strong>' . e($menuTitle) . '</strong><br>
                                    <small>Price: $' . number_format($price, 2) . ' x Qty: ' . $item->quantity . '</small>
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
                    ? "{$order->address->label}<br>{$order->address->address_line1}, {$order->address->address_line2}<br>{$order->address->city} - {$order->address->pincode}<br>Phone: {$order->address->phone}"
                    : 'N/A',
                'items'         => $itemHtmlList,
                'order_type'    => ucfirst($order->order_type),
                'order_date'    =>  Carbon::parse($order->order_date)->format('d-m-Y'),
                'status'        => ucfirst($order->status),
                'status_badge'  => $this->getStatusBadge($order->status),
                'total_amount'  => '$' . number_format($order->total_amount, 2),
                'created_at'    => Carbon::parse($order->created_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json([
            'data' => $data,
        ], 200);
    }

    private function getStatusBadge($status)
    {
        $colors = ['pending' => 'warning', 'confirmed' => 'success', 'rejected' => 'danger', 'preparing'   => 'info', 'ready' => 'secondary', 'dispatched'  => 'primary', 'delivered'   => 'success',];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>" . ucfirst($status) . "</span>";
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
                $addQuarantine_failed = Order_model::updateOrCreate(
                    ['id' => $request->input('hid_orderid')],
                    $updated_data
                );
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

 
}

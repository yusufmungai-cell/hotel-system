<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;

class POSController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::all();
        $items = MenuItem::where('active', 1)->get();

        $order = Order::where('status', 'open')->first();

        return view('pos.index', compact('categories', 'items', 'order'));
    }

    public function addToOrder(Request $request)
    {
        $item = MenuItem::findOrFail($request->item_id);

        $order = Order::firstOrCreate(
            ['status' => 'open'],
            ['order_no' => 'ORD' . time()]
        );

        $existing = OrderItem::where('order_id', $order->id)
                    ->where('menu_item_id', $item->id)
                    ->first();

        if ($existing) {
            $existing->qty += 1;
            $existing->total = $existing->qty * $existing->price;
            $existing->save();
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item->id,
                'qty' => 1,
                'price' => $item->price,
                'total' => $item->price
            ]);
        }

        $this->updateOrderTotal($order->id);

        return back();
    }

    public function checkout(Request $request)
{
    $order = Order::findOrFail($request->order_id);

    $order->payment_method = $request->payment_method;

    if ($request->payment_method == 'Room Charge') {

        $order->booking_id = $request->booking_id;

        // Add to room bill
        $booking = \App\Models\Booking::find($request->booking_id);

        $booking->total += $order->total;
        $booking->save();
    }

    $order->status = 'closed';
    $order->save();

    return redirect('/pos')->with('success', 'Order completed successfully');
}


    private function updateOrderTotal($order_id)
    {
        $order = Order::find($order_id);

        $total = OrderItem::where('order_id', $order_id)->sum('total');

        $order->total = $total;
        $order->save();
    }
}

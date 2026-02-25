<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    // Pickup screen
    public function index()
    {
        $orders = Order::where('status','ready_for_pickup')
            ->with('waiter')
            ->latest()
            ->get();

        return view('pickup.index', compact('orders'));
    }

    // Waiter collects food
    public function collect(Order $order)
    {
        // Move order to billing stage
        $order->update([
            'status' => 'served',
            'payment_status' => 'unpaid'
        ]);

        return back()->with('success','Order served. Awaiting payment.');
    }

    // polling check (for popup/sound)
    public function check()
    {
        $latest = Order::where('status','ready_for_pickup')->max('id') ?? 0;

        return response()->json([
            'latest_id' => $latest
        ]);
    }
}
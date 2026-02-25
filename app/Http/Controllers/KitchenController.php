<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class KitchenController extends Controller
{
    // ================= KITCHEN SCREEN =================
    public function index()
    {
        $orders = Order::whereHas('items', function($q){
                $q->where('station','kitchen')
                  ->where('status','pending');
            })
            ->with([
                'waiter', // 👈 load waiter
                'items' => function($q){
                    $q->where('station','kitchen')
                      ->where('status','pending')
                      ->with('menuItem');
                }
            ])
            ->latest()
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    // ================= CHECK NEW ORDERS =================
    public function check(Request $request)
    {
        $since = $request->since ?? now()->subMinutes(1);

        $count = Order::where('status','closed')
            ->where('created_at','>', $since)
            ->count();

        return response()->json([
            'new_orders' => $count
        ]);
    }

    // ================= MARK KITCHEN READY =================
    public function ready($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        // mark only kitchen items ready
        $order->items()
            ->where('station','kitchen')
            ->where('status','pending')
            ->update(['status'=>'ready']);

        // if everything ready -> ready for pickup
        if(!$order->items()->where('status','pending')->exists()){
            $order->update(['status'=>'ready_for_pickup']);
        }

        return back()->with('success','Kitchen items ready');
    }

    // ================= FINAL SERVED =================
    public function served($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'served';
        $order->save();

        return back();
    }
}
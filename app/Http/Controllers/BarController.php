<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class BarController extends Controller
{
    // ================= BAR SCREEN =================
    public function index()
    {
        $orders = Order::whereHas('items', function($q){
                $q->where('station','bar')
                  ->where('status','pending');
            })
            ->with([
                'waiter', // load waiter
                'items' => function($q){
                    $q->where('station','bar')
                      ->where('status','pending')
                      ->with('menuItem');
                }
            ])
            ->latest()
            ->get();

        return view('bar.index', compact('orders'));
    }

    // ================= MARK BAR READY =================
    public function ready($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        // mark only bar items ready
        $order->items()
            ->where('station','bar')
            ->where('status','pending')
            ->update(['status'=>'ready']);

        // if everything ready → ready for pickup
        if(!$order->items()->where('status','pending')->exists()){
            $order->update(['status'=>'ready_for_pickup']);
        }

        return back()->with('success','Bar items ready');
    }
}
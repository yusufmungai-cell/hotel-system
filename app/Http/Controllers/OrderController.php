<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\OrderItem;
use App\Models\Setting;

class OrderController extends Controller
{

    // ===============================
    // OPEN POS
    // ===============================
    public function index()
    {
        $order = Order::where('status','open')
            ->where('waiter_id',auth()->id())
            ->latest()
            ->first();

        if (!$order) {
            $order = Order::create([
    'order_no' => 'ORD'.time(),
    'status' => 'open',
    'total' => 0,
    'waiter_id' => auth()->id()
]);
        }

        return redirect()->route('orders.show',$order->id);
    }

    // ===============================
    // VIEW ORDER SCREEN
    // ===============================
    public function show($id)
    {
        $order = Order::with('items.menuItem')->findOrFail($id);
        $menuItems = MenuItem::where('is_active',1)->get();

        return view('pos.show', compact('order','menuItems'));
    }

    // ===============================
    // ADD ITEM (SMART MERGE + ROUTING)
    // ===============================
    public function addItem(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $menuItem = MenuItem::findOrFail($request->menu_item_id);

        // ROUTING SWITCH
        $routingEnabled = Setting::get('enable_station_routing',0);
        $station = $routingEnabled ? $menuItem->station : 'kitchen';

        $existing = $order->items()
            ->where('menu_item_id',$menuItem->id)
            ->where('station',$station)
            ->first();

        if($existing){
            $existing->qty += 1;
            $existing->total = $existing->qty * $existing->price;
            $existing->save();
        }else{
            $stationRouting = Setting::get('enable_station_routing',0);

$station = 'kitchen';
if($stationRouting){
    $station = $menuItem->station ?? 'kitchen';
}

$order->items()->create([
    'menu_item_id'=>$menuItem->id,
    'qty'=>1,
    'price'=>$menuItem->price,
    'total'=>$menuItem->price,
    'station'=>$menuItem->station ?? 'kitchen',
    'status'=>'pending'
]);
        }

        $order->update([
            'total'=>$order->items()->sum('total')
        ]);

        return back();
    }

    // ===============================
    // AJAX ADD ITEM
    // ===============================
    public function addItemAjax(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $menuItem = MenuItem::findOrFail($request->menu_item_id);

        $routingEnabled = Setting::get('enable_station_routing',0);
        $station = $routingEnabled ? $menuItem->station : 'kitchen';

        $item = $order->items()
            ->where('menu_item_id',$menuItem->id)
            ->where('station',$station)
            ->first();

        if($item){
            $item->qty++;
            $item->total = $item->qty * $item->price;
            $item->save();
        }else{
            $order->items()->create([
                'menu_item_id'=>$menuItem->id,
                'qty'=>1,
                'price'=>$menuItem->price,
                'total'=>$menuItem->price,
                'station'=>$station
            ]);
        }

        $order->update(['total'=>$order->items()->sum('total')]);

        return response()->json([
            'total'=>$order->total,
            'items'=>$order->items()->with('menuItem')->get()
        ]);
    }

    // ===============================
    // INCREASE
    // ===============================
    public function increase($orderId,$itemId)
    {
        $item = OrderItem::where('order_id',$orderId)->findOrFail($itemId);

        $item->qty++;
        $item->total = $item->qty * $item->price;
        $item->save();

        $item->order->update([
            'total'=>$item->order->items->sum('total')
        ]);

        return back();
    }

    // ===============================
    // DECREASE
    // ===============================
    public function decrease($orderId,$itemId)
    {
        $item = OrderItem::where('order_id',$orderId)->findOrFail($itemId);

        if($item->qty <= 1){
            $item->delete();
        }else{
            $item->qty--;
            $item->total = $item->qty * $item->price;
            $item->save();
        }

        $item->order->update([
            'total'=>$item->order->items->sum('total')
        ]);

        return back();
    }

    // ===============================
    // REMOVE
    // ===============================
    public function remove($orderId,$itemId)
    {
        $item = OrderItem::where('order_id',$orderId)->findOrFail($itemId);
        $order = $item->order;

        $item->delete();

        $order->update([
            'total'=>$order->items->sum('total')
        ]);

        return back();
    }

    // ===============================
    // SEND TO KITCHEN
    // ===============================
    public function close($id)
{
    $order = Order::with('items')->findOrFail($id);

    // mark order closed
    $order->status = 'closed';
    $order->save();

    // 🔥 VERY IMPORTANT
    foreach($order->items as $item){
        $item->status = 'pending';
        $item->save();
    }

    return redirect()->route('orders.index')
        ->with('success','Order sent to preparation stations');
}

    // ===============================
    // MARK SERVED
    // ===============================
    public function markServed(Order $order)
    {
        $order->kitchen_status = 'served';
        $order->save();

        return back()->with('success','Order served successfully');
    }
	public function checkReady()
{
    $readyOrders = Order::whereHas('items', function($q){
        $q->where('status','ready');
    })
    ->whereDoesntHave('items', function($q){
        $q->where('status','pending');
    })
    ->count();

    return response()->json([
        'ready'=>$readyOrders
    ]);
}
}
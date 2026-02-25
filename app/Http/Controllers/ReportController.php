<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function zReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $orders = Order::whereDate('created_at',$date)
            ->where('payment_status','paid')
            ->with('waiter')
            ->get();

        // totals
        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();

        $cash = $orders->where('payment_method','cash')->sum('total');
        $mpesa = $orders->where('payment_method','mpesa')->sum('total');
        $card = $orders->where('payment_method','card')->sum('total');

        // waiter performance
        $waiters = $orders->groupBy('waiter_id')->map(function($group){
            return [
                'name' => optional($group->first()->waiter)->name ?? 'Unknown',
                'orders' => $group->count(),
                'total' => $group->sum('total')
            ];
        });

        // top items
        $items = OrderItem::select('menu_item_id',
                DB::raw('SUM(qty) as qty'),
                DB::raw('SUM(total) as total'))
            ->whereHas('order',function($q) use ($date){
                $q->whereDate('created_at',$date)
                  ->where('payment_status','paid');
            })
            ->with('menuItem')
            ->groupBy('menu_item_id')
            ->orderByDesc('qty')
            ->get();

        return view('reports.z',compact(
            'date','totalSales','totalOrders','cash','mpesa','card','waiters','items'
        ));
    }
}
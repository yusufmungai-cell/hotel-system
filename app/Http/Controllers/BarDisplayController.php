<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarDisplayController extends Controller
{
   public function index()
{
    $orders = Order::whereHas('items', function($q){
        $q->where('station','bar')
          ->where('done',false);
    })
    ->with(['items'=>function($q){
        $q->where('station','bar')
          ->where('done',false);
    }])
    ->latest()
    ->get();

    return view('bar.index',compact('orders'));
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class BillingController extends Controller
{
    // Billing screen
    public function index()
    {
        $orders = Order::where('payment_status','unpaid')
            ->where('status','served')
            ->with(['items.menuItem','waiter'])
            ->latest()
            ->get();

        return view('billing.index', compact('orders'));
    }

    // Select payment method
    public function pay(Request $request, Order $order)
    {
        $method = $request->method;

        // CASH & CARD → instant payment
        if(in_array($method, ['cash','card'])){
            $order->update([
                'payment_method' => $method,
                'payment_status' => 'paid'
            ]);

            return redirect()->route('billing.receipt',$order->id);
        }

        // MPESA → wait for code
        if($method == 'mpesa'){
            $order->update([
                'payment_method' => 'mpesa',
                'payment_status' => 'waiting_mpesa'
            ]);

            return redirect()->route('billing.mpesa.form',$order->id);
        }
    }

    // Show MPESA input page
    public function mpesaForm(Order $order)
    {
        return view('billing.mpesa', compact('order'));
    }

    // Confirm MPESA code
    public function mpesaConfirm(Request $request, Order $order)
    {
        $request->validate([
            'code' => 'required|min:5|max:20'
        ]);

        $order->update([
            'mpesa_code' => strtoupper($request->code),
            'payment_status' => 'paid'
        ]);

        return redirect()->route('billing.receipt',$order->id);
    }
	public function receipt(Order $order)
{
    $order->load('items.menuItem','waiter');
    return view('billing.receipt', compact('order'));
}
}
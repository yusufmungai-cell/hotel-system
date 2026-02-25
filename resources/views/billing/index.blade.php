@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Billing / Cashier</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if($orders->count())

<div class="grid grid-cols-3 gap-6">

@foreach($orders as $order)

<div class="bg-white shadow rounded p-4">

    <div class="flex justify-between mb-2">
        <div class="font-bold text-lg">Order #{{ $order->id }}</div>
        <div class="text-sm text-gray-500">{{ $order->waiter->name ?? 'N/A' }}</div>
    </div>

    <hr class="mb-2">

    @foreach($order->items as $item)
    <div class="flex justify-between py-1">
        <span>{{ $item->menuItem->name }} x{{ $item->qty }}</span>
        <span>KSh {{ number_format($item->total,2) }}</span>
    </div>
    @endforeach

    <hr class="my-2">

    <div class="flex justify-between font-bold text-lg mb-3">
        <span>Total</span>
        <span>KSh {{ number_format($order->total,2) }}</span>
    </div>

    <div class="grid grid-cols-3 gap-2">

    {{-- CASH --}}
   <form method="POST" action="{{ route('billing.pay',$order->id) }}">
    @csrf
    <input type="hidden" name="method" value="cash">
    <button type="submit"
        class="w-full h-12 rounded-lg font-semibold text-white
               bg-green-600 hover:bg-green-700
               flex items-center justify-center">
        CASH
    </button>
</form>

{{-- MPESA --}}
<form method="POST" action="{{ route('billing.pay',$order->id) }}">
    @csrf
    <input type="hidden" name="method" value="mpesa">
    <button type="submit"
        class="w-full h-12 rounded-lg font-semibold text-white
               bg-blue-600 hover:bg-blue-700
               flex items-center justify-center">
        M-PESA
    </button>
</form>

{{-- CARD --}}
<form method="POST" action="{{ route('billing.pay',$order->id) }}">
    @csrf
    <input type="hidden" name="method" value="card">
    <button type="submit"
        class="w-full h-12 rounded-lg font-semibold text-white
               bg-gray-900 hover:bg-black border border-gray-700
               flex items-center justify-center">
        CARD
    </button>
</form>

</div>

</div>

@endforeach

</div>

@else

<div class="text-gray-400">No orders awaiting payment</div>

@endif

@endsection
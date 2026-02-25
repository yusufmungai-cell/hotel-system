@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Daily Z Report</h1>

<form class="mb-6">
    <input type="date" name="date" value="{{ $date }}" class="border p-2 rounded">
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Load</button>
</form>

<div class="grid grid-cols-3 gap-6">

<div class="bg-white p-5 rounded shadow">
    <h2 class="font-bold mb-3">Sales Summary</h2>
    <div>Total Orders: {{ $totalOrders }}</div>
    <div>Total Sales: <b>KSh {{ number_format($totalSales,2) }}</b></div>
    <hr class="my-2">
    <div>Cash: KSh {{ number_format($cash,2) }}</div>
    <div>M-Pesa: KSh {{ number_format($mpesa,2) }}</div>
    <div>Card: KSh {{ number_format($card,2) }}</div>
</div>

<div class="bg-white p-5 rounded shadow">
    <h2 class="font-bold mb-3">Waiter Performance</h2>
    @foreach($waiters as $w)
        <div class="flex justify-between border-b py-1">
            <span>{{ $w['name'] }} ({{ $w['orders'] }})</span>
            <span>KSh {{ number_format($w['total'],2) }}</span>
        </div>
    @endforeach
</div>

<div class="bg-white p-5 rounded shadow">
    <h2 class="font-bold mb-3">Top Items</h2>
    @foreach($items as $item)
        <div class="flex justify-between border-b py-1">
            <span>{{ $item->menuItem->name }} x{{ $item->qty }}</span>
            <span>KSh {{ number_format($item->total,2) }}</span>
        </div>
    @endforeach
</div>

</div>

@endsection
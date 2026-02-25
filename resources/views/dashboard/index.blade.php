@extends('layouts.app')

@section('content')

<div class="p-6 space-y-6">

    <!-- KPI CARDS -->
    <div class="grid grid-cols-4 gap-6">

        <div class="bg-green-100 p-4 rounded shadow">
            <h4 class="font-bold">Total Income</h4>
            <p class="text-2xl font-bold">KES {{ number_format($totalIncome,2) }}</p>
        </div>

        <div class="bg-red-100 p-4 rounded shadow">
            <h4 class="font-bold">Total Expense</h4>
            <p class="text-2xl font-bold">KES {{ number_format($totalExpense,2) }}</p>
        </div>

        <div class="bg-blue-100 p-4 rounded shadow">
            <h4 class="font-bold">Net Profit</h4>
            <p class="text-2xl font-bold">KES {{ number_format($netProfit,2) }}</p>
        </div>

        <div class="bg-purple-100 p-4 rounded shadow">
            <h4 class="font-bold">Stock Value</h4>
            <p class="text-2xl font-bold">KES {{ number_format($stockValue,2) }}</p>
        </div>

    </div>

    <!-- TODAY -->
    <div class="bg-yellow-100 p-4 rounded shadow">
        <h4 class="font-bold mb-2">Today's Income</h4>
        <p class="text-xl font-bold">KES {{ number_format($todayIncome,2) }}</p>
    </div>

    <!-- STOCK SUMMARY -->
    <div class="grid grid-cols-2 gap-6">

        <div class="bg-white p-4 rounded shadow">
            <h4 class="font-bold mb-2">Stock Summary</h4>
            <p>Total Items: {{ $totalStockItems }}</p>
            <p>Supplier Payable: KES {{ number_format($supplierPayable,2) }}</p>
        </div>

        <div class="bg-red-50 p-4 rounded shadow">
            <h4 class="font-bold mb-2 text-red-600">Low Stock Alert</h4>

            @if($lowStock->count() > 0)
                <ul class="text-sm space-y-1">
                    @foreach($lowStock as $item)
                        <li class="flex justify-between">
                            <span>{{ $item->name }}</span>
                            <span class="text-red-600 font-bold">{{ $item->stock }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-green-600">All stock levels healthy ✔</p>
            @endif
        </div>

    </div>

</div>

@endsection

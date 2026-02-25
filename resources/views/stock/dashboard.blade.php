@extends('layouts.app')

@section('content')

<x-slot name="header">Stock Dashboard</x-slot>

<div class="grid grid-cols-4 gap-6">

<div class="bg-white p-4 shadow rounded">
<h4>Total Ingredients</h4>
<p class="text-2xl font-bold">{{ $totalIngredients }}</p>
</div>

<div class="bg-white p-4 shadow rounded">
<h4>Total Stock Qty</h4>
<p class="text-2xl font-bold">{{ $totalStock }}</p>
</div>

<div class="bg-white p-4 shadow rounded">
<h4>Low Stock Items</h4>
<p class="text-2xl font-bold text-red-600">{{ $lowStock }}</p>
</div>

<div class="bg-white p-4 shadow rounded">
<h4>Total Stock Value</h4>
<p class="text-2xl font-bold">KES {{ number_format($totalValue,2) }}</p>
</div>

</div>

@endsection

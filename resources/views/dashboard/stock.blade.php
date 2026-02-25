@extends('layouts.app')

@section('content')

<div class="grid grid-cols-3 gap-6 mb-6">

    <div class="bg-blue-100 p-4 rounded">
        <h4 class="text-lg font-bold">Total Ingredients</h4>
        <p class="text-2xl">{{ $totalIngredients }}</p>
    </div>

    <div class="bg-green-100 p-4 rounded">
        <h4 class="text-lg font-bold">Total Stock Quantity</h4>
        <p class="text-2xl">{{ $totalStock }}</p>
    </div>

    <div class="bg-red-100 p-4 rounded">
        <h4 class="text-lg font-bold">Low Stock Items</h4>
        <p class="text-2xl">{{ $lowStock->count() }}</p>
    </div>

</div>


<h3 class="font-bold mb-3">Low Stock Alert</h3>

@if($lowStock->count() > 0)

<table class="w-full border mb-6">
<tr class="bg-gray-100">
<th class="p-2">Ingredient</th>
<th class="p-2">Stock</th>
<th class="p-2">Reorder Level</th>
</tr>

@foreach($lowStock as $item)
<tr>
<td class="p-2">{{ $item->name }}</td>
<td class="p-2 text-red-600">{{ $item->stock }}</td>
<td class="p-2">{{ $item->reorder_level }}</td>
</tr>
@endforeach

</table>

@else
<p class="text-green-600">All stock levels are healthy.</p>
@endif


<h3 class="font-bold mb-3">Recent Stock Movements</h3>

<table class="w-full border">
<tr class="bg-gray-100">
<th class="p-2">Date</th>
<th class="p-2">Ingredient</th>
<th class="p-2">Type</th>
<th class="p-2">Qty</th>
<th class="p-2">After Qty</th>
</tr>

@foreach($recentMovements as $m)
<tr>
<td class="p-2">{{ $m->created_at }}</td>
<td class="p-2">{{ $m->ingredient->name ?? '-' }}</td>
<td class="p-2">{{ $m->type }}</td>
<td class="p-2">{{ $m->qty }}</td>
<td class="p-2">{{ $m->after_qty }}</td>
</tr>
@endforeach

</table>

@endsection

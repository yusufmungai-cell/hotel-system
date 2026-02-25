@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<h3 class="mb-3">Receive Goods</h3>

<form method="POST">
@csrf

<select name="supplier_id" class="border p-2 mb-2">
@foreach($suppliers as $s)
<option value="{{ $s->id }}">{{ $s->name }}</option>
@endforeach
</select>

<input name="reference" placeholder="Reference No" class="border p-2 mb-2">

<select name="ingredient_id" class="border p-2 mb-2 w-full">
    <option value="">Select Ingredient</option>
    @foreach(\App\Models\Ingredient::all() as $ingredient)
        <option value="{{ $ingredient->id }}">
            {{ $ingredient->name }}
        </option>
    @endforeach
</select>

<input name="qty" placeholder="Quantity" class="border p-2 mb-2">
<input name="price" placeholder="Unit Price" class="border p-2 mb-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Receive Items
</button>

</form>

<hr class="my-4">

<h3>Receiving History</h3>

@foreach($receivings as $r)

<div class="border p-3 mb-3">

<strong>Supplier:</strong> {{ $r->supplier->name }} <br>
<strong>Total:</strong> {{ $r->total }} <br>
<strong>Date:</strong> {{ $r->created_at }} <br>

<table class="w-full mt-2 border">
<tr class="bg-gray-100">
<th class="p-2">Item</th>
<th class="p-2">Qty</th>
<th class="p-2">Price</th>
<th class="p-2">Total</th>
</tr>

@foreach($r->items as $i)
<tr>
<td class="p-2">{{ $i->ingredient->name ?? '' }}</td>
<td class="p-2">{{ $i->qty }}</td>
<td class="p-2">{{ $i->price }}</td>
<td class="p-2">{{ $i->total }}</td>
</tr>
@endforeach

</table>

</div>

@endforeach



@endsection

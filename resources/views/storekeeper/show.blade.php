@extends('layouts.app')

@section('content')

<table class="table w-full border mb-4">
<tr>
<th>Ingredient</th>
<th>Quantity</th>
</tr>

@foreach($request->items as $item)
<tr>
<td>{{ $item->ingredient->name }}</td>
<td>{{ $item->qty }}</td>
</tr>
@endforeach
</table>

<form method="POST" action="/storekeeper/approve/{{ $request->id }}">
@csrf
<button class="bg-green-600 text-white px-4 py-2">Approve & Deduct Stock</button>
</form>

<br>

<form method="POST" action="/storekeeper/reject/{{ $request->id }}">
@csrf
<button class="bg-red-600 text-white px-4 py-2">Reject Request</button>
</form>

@endsection

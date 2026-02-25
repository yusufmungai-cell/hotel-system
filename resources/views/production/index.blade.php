@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<h3>Request Items from Store</h3>

<form method="POST">
@csrf

<select name="ingredient_id" class="border p-2 mb-2" required>
    <option value="">-- Select Ingredient --</option>

    @foreach($ingredients as $i)
        <option value="{{ $i->id }}">{{ $i->name }}</option>
    @endforeach
</select>

<input name="qty" placeholder="Quantity (numbers only)" 
       class="border p-2 mb-2" required type="number">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Request Item
</button>

</form>


<hr>

<h3>Request History</h3>

<h3 class="text-lg font-bold mt-6 mb-2">Request History</h3>

<table class="w-full border">
    <tr class="bg-gray-100">
        <th class="p-2 text-left">ID</th>
        <th class="p-2 text-left">Requested Items</th>
        <th class="p-2 text-left">Status</th>
        <th class="p-2 text-left">Date</th>
    </tr>

    @foreach($requests as $req)
    <tr class="border-b">
        <td class="p-2">{{ $req->id }}</td>

        <td class="p-2">
            @foreach($req->items as $item)
                - {{ $item->ingredient->name ?? 'Unknown' }} : {{ $item->qty }} <br>
            @endforeach
        </td>

        <td class="p-2">{{ $req->status }}</td>

        <td class="p-2">{{ $req->created_at }}</td>
    </tr>
    @endforeach

</table>
@if($requests->where('status','pending')->first())
<form method="POST" action="/production/submit">
    @csrf
    <button class="bg-green-600 text-white px-4 py-2 rounded mt-3">
        Submit Current Request to Store
    </button>
</form>
@endif


@endsection

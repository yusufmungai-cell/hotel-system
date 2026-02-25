@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<form method="POST">
@csrf

<input name="name" placeholder="Type Name" class="border p-2 mb-2">
<input name="price" placeholder="Price" class="border p-2 mb-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Add Room Type
</button>

</form>

<hr>

<table class="w-full mt-3">
<tr>
<th>Name</th>
<th>Price</th>
</tr>

@foreach($types as $t)
<tr>
<td>{{ $t->name }}</td>
<td>{{ $t->price }}</td>
</tr>
@endforeach

</table>

@endsection

@extends('layouts.app')

@section('content')

<x-slot name="header">Suppliers</x-slot>

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<h3 class="mb-3">Add Supplier</h3>

<form method="POST">
@csrf

<input name="name" placeholder="Supplier Name" class="border p-2 mb-2">
<input name="phone" placeholder="Phone" class="border p-2 mb-2">
<input name="email" placeholder="Email" class="border p-2 mb-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Add Supplier
</button>

</form>

<hr>

<h3>Existing Suppliers</h3>

<table class="w-full mt-3">
<tr>
<th>Name</th>
<th>Phone</th>
<th>Balance</th>
</tr>

@foreach($suppliers as $s)
<tr>
<td>{{ $s->name }}</td>
<td>{{ $s->phone }}</td>
<td>{{ $s->balance }}</td>
</tr>
@endforeach

</table>

@endsection

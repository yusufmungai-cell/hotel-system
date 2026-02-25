@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Add Menu Item</h1>

<form method="POST" action="{{ route('menu.store') }}" class="bg-white p-6 rounded shadow w-96">
@csrf

<label class="block mb-2">Item Name</label>
<input type="text" name="name" class="border p-2 w-full mb-4">

<label class="block mb-2">Price</label>
<input type="number" step="0.01" name="price" class="border p-2 w-full mb-4">

<label class="block mb-2 font-semibold">Preparation Station</label>
<select name="station" class="border p-2 w-full mb-6">
    <option value="kitchen">Kitchen (Food)</option>
    <option value="bar">Bar (Drinks)</option>
</select>

<button class="bg-green-600 text-white px-4 py-2 rounded">
    Save Item
</button>

</form>
@endsection
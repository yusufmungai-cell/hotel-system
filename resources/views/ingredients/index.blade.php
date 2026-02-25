@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4">Ingredients</h1>

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-4 rounded">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-4 rounded shadow mb-6">
    <form method="POST">
        @csrf

        <div class="grid grid-cols-3 gap-3">
            <input name="name" placeholder="Ingredient Name"
                class="border p-2 rounded">

            <input name="unit" placeholder="Unit (kg, pcs, litre)"
                class="border p-2 rounded">

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add Ingredient
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr class="text-left">
                <th class="p-3">Name</th>
                <th class="p-3">Unit</th>
                <th class="p-3">Stock</th>
            </tr>
        </thead>

        <tbody>
        @forelse($ingredients as $i)
            <tr class="border-t">
                <td class="p-3">{{ $i->name }}</td>
                <td class="p-3">{{ $i->unit }}</td>
                <td class="p-3 font-semibold">{{ $i->stock }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-400">
                    No ingredients added yet
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection

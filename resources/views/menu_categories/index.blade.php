@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="bg-green-200 p-2 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-200 p-2 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<!-- Add Category -->
<form method="POST" action="/menu-categories" class="mb-6">
    @csrf
    <input type="text" name="name" placeholder="Category name"
           class="border p-2 rounded" required>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Add Category
    </button>
</form>

<table class="w-full border">
    <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">Name</th>
            <th class="border p-2">Items</th>
            <th class="border p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td class="border p-2">
                <form method="POST" action="/menu-categories/{{ $category->id }}">
                    @csrf
                    @method('PUT')
                    <input type="text"
                           name="name"
                           value="{{ $category->name }}"
                           class="border p-1 rounded">
                    <button class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">
                        Update
                    </button>
                </form>
            </td>

            <td class="border p-2 text-center">
                {{ $category->items()->count() }}
            </td>

            <td class="border p-2 text-center">
                <form method="POST"
                      action="/menu-categories/{{ $category->id }}"
                      onsubmit="return confirm('Delete this category?')">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

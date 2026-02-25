@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Menu Items</h1>

<a href="{{ route('menu.create') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
   + Add Item
</a>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Name</th>
            <th class="p-3">Price</th>
            <th class="p-3">Station</th>
            <th class="p-3">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr class="border-t">
            <td class="p-3">{{ $item->name }}</td>
            <td class="p-3">KSh {{ $item->price }}</td>
            <td class="p-3 font-bold">{{ ucfirst($item->station) }}</td>
            <td class="p-3">
                <a href="{{ route('menu.edit',$item->id) }}" class="text-blue-600">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
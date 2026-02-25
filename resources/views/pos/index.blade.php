@extends('layouts.app')

@section('content')
<div class="flex h-[85vh] gap-4">

    {{-- MENU ITEMS --}}
    <div class="w-2/3 bg-white rounded shadow p-4 overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Menu</h2>

        <div class="grid grid-cols-4 gap-4">

            @foreach($menuItems as $item)
            <form method="POST" action="{{ route('orders.addItem', $order->id) }}">
                @csrf
                <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                <input type="hidden" name="qty" value="1">

                <button type="submit"
                    class="border rounded-lg p-3 hover:bg-green-50 w-full h-36 flex flex-col items-center justify-center">

                    {{-- IMAGE --}}
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" class="h-16 mb-2 object-cover">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded mb-2 flex items-center justify-center">
                            🍽️
                        </div>
                    @endif

                    <span class="font-semibold text-sm text-center">{{ $item->name }}</span>
                    <span class="text-green-600 font-bold">KSh {{ number_format($item->price,2) }}</span>

                </button>
            </form>
            @endforeach

        </div>
    </div>


    {{-- CURRENT ORDER --}}
    <div class="w-1/3 bg-white rounded shadow p-4 flex flex-col">
        <h2 class="text-xl font-bold mb-4">Current Order</h2>

        <div class="flex-1 overflow-y-auto">

           @forelse($order->items as $row)
<div class="flex justify-between items-center border-b py-2">

    {{-- ITEM NAME --}}
    <div class="w-1/2 font-medium">
        {{ $row->menuItem->name }}
    </div>

    {{-- QUANTITY CONTROLS --}}
    <div class="flex items-center gap-2">

        {{-- DECREASE --}}
        <form method="POST" action="{{ route('orders.decrease',$row->id) }}">
            @csrf
            <button class="bg-red-500 text-white w-7 h-7 rounded">−</button>
        </form>

        <span class="w-6 text-center">{{ $row->qty }}</span>

        {{-- INCREASE --}}
        <form method="POST" action="{{ route('orders.increase',$row->id) }}">
            @csrf
            <button class="bg-green-600 text-white w-7 h-7 rounded">+</button>
        </form>

    </div>

    {{-- TOTAL --}}
    <div class="w-24 text-right font-semibold">
        KSh {{ number_format($row->total,2) }}
    </div>

    {{-- DELETE --}}
    <form method="POST" action="{{ route('orders.remove',$row->id) }}">
        @csrf
        <button class="text-red-600 font-bold text-lg ml-2">×</button>
    </form>

</div>
@empty
            <div class="text-gray-400 text-center mt-10">
                No items added yet
            </div>
            @endforelse

        </div>

        <div class="border-t pt-4">
            <div class="flex justify-between font-bold text-lg mb-4">
                <span>Total</span>
                <span>KSh {{ number_format($order->total,2) }}</span>
            </div>

            <form method="POST" action="{{ route('orders.close', $order->id) }}">
                @csrf
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded text-lg">
                    Send to Kitchen
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
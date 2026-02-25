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

                <button class="border rounded-lg p-3 hover:bg-green-50 w-full h-36 flex flex-col items-center justify-center">

                    {{-- IMAGE --}}
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" class="h-16 mb-2 object-cover">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded mb-2 flex items-center justify-center text-2xl">
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
        <h2 class="text-xl font-bold mb-4">Current Order #{{ $order->order_no }}</h2>

        <div id="orderItems" class="flex-1 overflow-y-auto">

            @forelse($order->items as $row)

<div class="flex justify-between items-center border-b py-3 text-lg">

    {{-- ITEM NAME --}}
    <div class="flex-1">
        <div class="font-semibold">
            {{ $row->menuItem->name }}
        </div>

        <div class="text-sm text-gray-500">
            KSh {{ number_format($row->price,2) }}
        </div>
    </div>


    {{-- QUANTITY CONTROLS --}}
    <div class="flex items-center gap-3">

    {{-- DECREASE --}}
    <form method="POST" action="{{ route('orders.decrease', [$order->id,$row->id]) }}">
        @csrf
        <button type="submit"
            style="background:#dc2626;color:white;width:44px;height:44px;font-size:24px;font-weight:bold;border-radius:12px;box-shadow:0 3px 8px rgba(0,0,0,.35)">
            −
        </button>
    </form>

    {{-- QTY --}}
    <span style="font-size:22px;font-weight:bold;width:40px;text-align:center;">
        {{ $row->qty }}
    </span>

    {{-- INCREASE --}}
    <form method="POST" action="{{ route('orders.increase', [$order->id,$row->id]) }}">
        @csrf
        <button type="submit"
            style="background:#16a34a;color:white;width:44px;height:44px;font-size:24px;font-weight:bold;border-radius:12px;box-shadow:0 3px 8px rgba(0,0,0,.35)">
            +
        </button>
    </form>

</div>
    {{-- LINE TOTAL --}}
    <div class="w-24 text-right font-semibold">
        KSh {{ number_format($row->total,2) }}
    </div>

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
                <span id="orderTotal">KSh {{ number_format($order->total,2) }}</span>
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
<audio id="pickupSound">
    <source src="{{ asset('sounds/ready.mp3') }}" type="audio/mpeg">
</audio>

<script>
let lastReady = 0;

setInterval(() => {

    fetch("{{ route('kitchen.checkReady') }}")
    .then(res => res.json())
    .then(data => {

        if(data.ready_id > lastReady){

            lastReady = data.ready_id;

            document.getElementById('pickupSound').play();

            alert("ORDER READY FOR PICKUP!");

        }

    });

},3000);
</script>
@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Kitchen Display</h1>

@if($orders->count())

<div class="grid grid-cols-3 gap-6">

@foreach($orders as $order)

@php
$minutes = $order->created_at->timezone(config('app.timezone'))->diffInMinutes(now());

$color = 'bg-green-100 border-green-400';
if($minutes >= 5)  $color = 'bg-yellow-100 border-yellow-400';
if($minutes >= 15) $color = 'bg-orange-100 border-orange-500';
if($minutes >= 30) $color = 'bg-red-200 border-red-600';
@endphp

<div class="border-l-8 {{ $color }} rounded p-4 shadow">

    <div class="flex justify-between items-center">
        <div>
    <div class="text-2xl font-bold">
        Order #{{ $order->id }}
    </div>

    <div class="text-sm text-blue-700 font-semibold">
        Waiter: {{ $order->waiter->name ?? 'Unknown' }}
    </div>
</div>
<div class="text-sm text-blue-700 font-semibold">
    {{ $order->waiter->name ?? 'Unknown waiter' }}
</div>
        <div class="text-right">
            <div class="text-sm text-gray-600">
                {{ $order->created_at->timezone(config('app.timezone'))->format('H:i') }}
            </div>
            <div class="font-bold">
                {{ $minutes }} min
            </div>
        </div>
    </div>

    <hr class="my-2">

    {{-- ITEMS --}}
    @foreach($order->items as $item)
        <div class="flex justify-between text-lg py-1">
            <span>{{ $item->menuItem->name }}</span>
            <span class="font-bold">x{{ $item->qty }}</span>
        </div>
    @endforeach
{{-- READY BUTTON --}}
<form method="POST" action="{{ route('kitchen.ready',$order->id) }}" class="mt-4">
    @csrf
    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded text-lg font-bold">
        KITCHEN READY
    </button>
</form>
   {{-- MARK SERVED --}}


</div>

@endforeach

</div>

@else

<div class="text-gray-400 text-lg">No orders in kitchen</div>

@endif


{{-- SOUND --}}
<audio id="newOrderSound">
    <source src="{{ asset('sounds/new-order.mp3') }}" type="audio/mpeg">
</audio>

<script>
let kitchenOpenedAt = new Date().toISOString();

setInterval(() => {

    fetch("{{ route('kitchen.check') }}?since=" + kitchenOpenedAt)
        .then(res => res.json())
        .then(data => {

            if(data.new_orders > 0){

                // PLAY SOUND
                const audio = document.getElementById('newOrderSound');
                audio.currentTime = 0;
                audio.play().catch(()=>{});

                // POPUP FLASH
                document.body.classList.add('bg-red-300');
                setTimeout(()=>document.body.classList.remove('bg-red-300'),1000);

                // RELOAD ORDERS
                setTimeout(()=>location.reload(),1200);
            }

        });

}, 5000);
</script>
@endsection
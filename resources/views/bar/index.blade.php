@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Bar Display</h1>

@if($orders->count())

<div class="grid grid-cols-3 gap-6">

@foreach($orders as $order)

@php
$minutes = now()->diffInMinutes($order->created_at);

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

    <div class="text-sm text-purple-700 font-semibold">
        Waiter: {{ $order->waiter->name ?? 'Unknown' }}
    </div>
</div>
<div class="text-sm text-blue-700 font-semibold">
    {{ $order->waiter->name ?? 'Unknown waiter' }}
</div>
        <div class="text-right">
            <div class="text-sm text-gray-600">
                {{ $order->created_at->format('H:i') }}
            </div>
            <div class="font-bold">
                {{ $minutes }} min
            </div>
        </div>
    </div>

    <hr class="my-2">

    {{-- BAR ITEMS ONLY --}}
    @foreach($order->items as $item)
        <div class="flex justify-between text-lg py-1">
            <span>{{ $item->menuItem->name }}</span>
            <span class="font-bold">x{{ $item->qty }}</span>
        </div>
    @endforeach

    {{-- SINGLE READY BUTTON --}}
    <form method="POST" action="{{ route('bar.ready',$order->id) }}" class="mt-4">
        @csrf
        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded text-lg font-bold">
            BAR READY
        </button>
    </form>

</div>

@endforeach

</div>

@else
<div class="text-gray-400 text-lg">No bar orders</div>
@endif

@endsection
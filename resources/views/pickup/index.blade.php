@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-6 text-center">READY FOR PICKUP</h1>

@if($orders->count())

<div class="grid grid-cols-3 gap-6">

@foreach($orders as $order)

<div class="bg-green-100 border-4 border-green-500 rounded-xl p-6 text-center shadow-lg">

    <div class="text-5xl font-bold mb-2">
        #{{ $order->id }}
    </div>

    <div class="text-xl font-semibold mb-4">
        {{ $order->waiter->name ?? 'Waiter' }}
    </div>

    <form method="POST" action="{{ route('pickup.collect',$order->id) }}">
        @csrf
        <button class="bg-black text-white px-6 py-3 rounded-lg text-lg">
            COLLECT
        </button>
    </form>

</div>

@endforeach

</div>

@else

<div class="text-center text-2xl text-gray-400 mt-20">
No orders ready
</div>

@endif


{{-- SOUND --}}
<audio id="pickupSound">
    <source src="{{ asset('sounds/new-order.mp3') }}" type="audio/mpeg">
</audio>

<script>
let lastReady = {{ $orders->max('id') ?? 0 }};

setInterval(()=>{
    fetch("{{ route('pickup.check') }}")
    .then(res=>res.json())
    .then(data=>{
        if(data.latest_id > lastReady){
            document.getElementById('pickupSound').play().catch(()=>{});
            location.reload();
        }
    });
},4000);
</script>

@endsection
@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<h3 class="mb-3">Check In Guest</h3>

<form method="POST" action="/bookings/checkin">
@csrf

<input name="name" placeholder="Guest Name" class="border p-2 mb-2">
<input name="phone" placeholder="Phone" class="border p-2 mb-2">

<select name="room_id" class="border p-2 mb-2">
@foreach($rooms as $r)
<option value="{{ $r->id }}">
Room {{ $r->room_number }} - {{ $r->type->name }}
</option>
@endforeach
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Check In
</button>

</form>

<hr>

<h3>Current Bookings</h3>

<table class="w-full mt-3">
<tr>
<th>Guest</th>
<th>Room</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($bookings as $b)
<tr>
<td>{{ $b->guest->name }}</td>
<td>{{ $b->room->room_number }}</td>
<td>{{ $b->status }}</td>

<td>
@if($b->status == 'checked_in')
<a href="/bookings/checkout/{{ $b->id }}" class="text-red-600">
Checkout
</a>
@endif
</td>

</tr>
@endforeach

</table>
@endsection


@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<form method="POST">
@csrf

<select name="room_type_id" class="border p-2 mb-2">
@foreach($types as $t)
<option value="{{ $t->id }}">{{ $t->name }}</option>
@endforeach
</select>

<input name="room_number" placeholder="Room Number" class="border p-2 mb-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Add Room
</button>

</form>

<hr>

<table class="w-full mt-3">
<tr>
<th>Room</th>
<th>Type</th>
<th>Status</th>
</tr>

@foreach($rooms as $r)
<tr>
<td>{{ $r->room_number }}</td>
<td>{{ $r->type->name }}</td>
<td>{{ $r->status }}</td>
</tr>
@endforeach

</table>

@endsection

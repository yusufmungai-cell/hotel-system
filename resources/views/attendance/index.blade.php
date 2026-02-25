@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">My Attendance</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif


<!-- STATUS CARD -->
<div class="bg-white shadow rounded p-6 mb-6">

    <h2 class="text-lg font-semibold mb-3">Today's Status</h2>

    @if($todayAttendance)

        <p><strong>Date:</strong> {{ $todayAttendance->date }}</p>
        <p><strong>Clock In:</strong> {{ $todayAttendance->time_in }}</p>
        <p><strong>Clock Out:</strong> {{ $todayAttendance->time_out ?? '—' }}</p>

        @if(!$todayAttendance->time_out)
            <p class="text-green-600 font-bold mt-2">You are currently CLOCKED IN</p>
        @else
            <p class="text-blue-600 font-bold mt-2">You finished today's shift</p>
        @endif

    @else
        <p class="text-gray-600">No attendance record today</p>
    @endif

</div>


<!-- BUTTONS -->
<div class="flex gap-4 mb-8">

    <form method="POST" action="/attendance/clockin">
        @csrf
        <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded text-lg shadow">
            Clock In
        </button>
    </form>

    <form method="POST" action="/attendance/clockout">
        @csrf
        <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded text-lg shadow">
            Clock Out
        </button>
    </form>

</div>


<!-- HISTORY -->
<h2 class="text-xl font-semibold mb-3">Recent Attendance</h2>

<table class="w-full bg-white shadow rounded overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Date</th>
            <th class="p-3 text-left">In</th>
            <th class="p-3 text-left">Out</th>
            <th class="p-3 text-left">Hours</th>
        </tr>
    </thead>
    <tbody>

    @foreach($attendances as $a)
        <tr class="border-t">
            <td class="p-3">{{ $a->date }}</td>
            <td class="p-3">{{ $a->time_in }}</td>
            <td class="p-3">{{ $a->time_out ?? '—' }}</td>
            <td class="p-3">{{ $a->worked_hours ?? '-' }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

@endsection
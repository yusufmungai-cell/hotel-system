@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">System Settings</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('settings.update') }}" class="bg-white shadow rounded p-6 space-y-6">
@csrf

{{-- KITCHEN ROUTING TOGGLE --}}
<div class="flex items-center justify-between border-b pb-4">

    <div>
        <div class="font-semibold text-lg">Kitchen Routing</div>
        <div class="text-sm text-gray-500">
            Send items to different preparation stations
        </div>
    </div>

    <label class="flex items-center gap-3 text-lg font-semibold">
        <input type="checkbox" name="enable_station_routing" value="1"
            {{ ($settings['enable_station_routing'] ?? 0) ? 'checked' : '' }}
            class="w-5 h-5">

        <span class="text-gray-700">Enable</span>
    </label>

</div>

<button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded">
    Save Settings
</button>

</form>

@endsection
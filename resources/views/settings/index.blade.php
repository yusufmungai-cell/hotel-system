@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Hotel Settings</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data"
          class="bg-white shadow rounded p-6 space-y-5">

        @csrf

        <!-- COMPANY NAME -->
        <div>
            <label class="block text-sm font-medium mb-1">Hotel Name</label>
            <input type="text" name="company_name"
                   value="{{ $settings->company_name ?? '' }}"
                   class="w-full border rounded p-2">
        </div>

        <!-- PHONE -->
        <div>
            <label class="block text-sm font-medium mb-1">Phone</label>
            <input type="text" name="phone"
                   value="{{ $settings->phone ?? '' }}"
                   class="w-full border rounded p-2">
        </div>

        <!-- EMAIL -->
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email"
                   value="{{ $settings->email ?? '' }}"
                   class="w-full border rounded p-2">
        </div>

        <!-- ADDRESS -->
        <div>
            <label class="block text-sm font-medium mb-1">Address</label>
            <textarea name="address"
                      class="w-full border rounded p-2">{{ $settings->address ?? '' }}</textarea>
        </div>

        <!-- CURRENCY -->
        <div>
            <label class="block text-sm font-medium mb-1">Currency</label>
            <select name="currency" class="w-full border rounded p-2">
                <option value="KES" {{ ($settings->currency ?? '') == 'KES' ? 'selected' : '' }}>KES</option>
                <option value="USD" {{ ($settings->currency ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                <option value="UGX" {{ ($settings->currency ?? '') == 'UGX' ? 'selected' : '' }}>UGX</option>
                <option value="TZS" {{ ($settings->currency ?? '') == 'TZS' ? 'selected' : '' }}>TZS</option>
            </select>
        </div>

        <!-- LOGO -->
        <div>
            <label class="block text-sm font-medium mb-1">Hotel Logo</label>
            <input type="file" name="logo" class="w-full">

            @if(!empty($settings->logo))
                <img src="{{ asset('storage/'.$settings->logo) }}"
                     class="h-20 mt-3 border rounded p-1">
            @endif
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded shadow">
            Save Settings
        </button>

    </form>
	<hr class="my-10">

<h2 class="text-xl font-bold mb-4">Restaurant System</h2>

<form method="POST" action="{{ route('settings.update') }}" class="bg-white p-6 rounded shadow w-[500px]">
@csrf

<div class="flex items-center justify-between mb-6">

    <div>
        <div class="font-bold text-lg">Enable Station Routing</div>
        <div class="text-gray-500 text-sm">
            Food → Kitchen | Drinks → Bar
        </div>
    </div>

    <label class="inline-flex items-center cursor-pointer">
        <input type="checkbox" name="routing" value="1" class="sr-only peer"
            {{ \App\Models\Setting::get('enable_station_routing',0) ? 'checked' : '' }}>
        <div class="w-14 h-7 bg-gray-300 peer-checked:bg-green-500 rounded-full relative transition">
            <div class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition peer-checked:translate-x-7"></div>
        </div>
    </label>

</div>

<button class="bg-blue-600 text-white px-6 py-2 rounded">
    Save Restaurant Settings
</button>

</form>

</div>

@endsection
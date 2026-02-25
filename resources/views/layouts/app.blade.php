<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen w-full">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white p-4 space-y-3">

        <h2 class="text-xl font-bold mb-6">Hotel System</h2>

        <a href="/dashboard" class="menu-link">Dashboard</a>

        <hr class="my-3 border-gray-700">

        @auth
@if(
    auth()->user()->hasRole('Admin') ||
    auth()->user()->hasRole('Storekeeper') ||
    auth()->user()->hasRole('Kitchen')
)

<hr class="my-3">

<div class="menu-title">STORES & KITCHEN</div>

{{-- Storekeeper & Admin --}}
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Storekeeper'))
<a href="/ingredients" class="menu-link">Ingredients</a>
<a href="/receivings" class="menu-link">Receiving</a>
<a href="/storekeeper/requests" class="menu-link">Storekeeper Requests</a>
@endif

{{-- Kitchen & Admin --}}
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Kitchen'))
<a href="/production" class="menu-link">Production</a>
@endif

@endif
@endauth

        <hr class="my-3 border-gray-700">

       @auth
@if(
    auth()->user()->hasRole('Admin') ||
    auth()->user()->hasRole('Cashier') ||
    auth()->user()->hasRole('Restaurant')
)

<hr class="my-3">

<hr class="my-3 border-gray-700">

{{-- ================= RESTAURANT ================= --}}
<p class="text-gray-400 uppercase text-xs px-2 mt-2">Restaurant</p>
<a href="{{ route('billing.index') }}" class="block hover:bg-gray-700 p-2 rounded">
    Pending Bills
</a>

<a href="{{ route('pos.index') }}" class="block hover:bg-gray-700 p-2 rounded">
    POS / Cashier
</a>

<a href="{{ route('orders.index') }}" class="block hover:bg-gray-700 p-2 rounded">
    Orders
</a>

<a href="{{ route('bar.display') }}" class="block hover:bg-gray-700 p-2 rounded">
    Bar Display
</a>

{{-- Pickup Screen (Waiters collect ready food here) --}}
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Restaurant'))
<a href="{{ route('pickup.index') }}" class="block hover:bg-gray-700 p-2 rounded bg-green-700">
    Pickup Screen
</a>
@endif

{{-- ================= KITCHEN ================= --}}
<p class="text-gray-400 uppercase text-xs px-2 mt-4">Kitchen</p>

<a href="{{ route('kitchen.index') }}" class="block hover:bg-gray-700 p-2 rounded">
    Kitchen Display
</a>

@endif
@endauth

        <hr class="my-3 border-gray-700">

        @auth
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Reception'))

<hr class="my-3">

<div class="menu-title">HOTEL</div>

<a href="/rooms" class="menu-link">Rooms</a>
<a href="/bookings" class="menu-link">Bookings</a>

@endif
@endauth

        <hr class="my-3 border-gray-700">

        @if(auth()->user()->employee)
<li>
    @auth
    @if(auth()->user()->employee)
        <a href="/attendance" class="menu-link">My Attendance</a>
    @endif
@endauth
</li>
@endif
        @auth
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('System Admin'))

<hr class="my-3">

<div class="menu-title">HR MANAGEMENT</div>

<a href="/employees" class="menu-link">Employees</a>
<a href="/attendance/approval" class="menu-link">Approve Attendance</a>
<a href="/payroll" class="menu-link">Payroll</a>
<a href="{{ route('loans.index') }}" class="menu-link">Employee Loans</a>
@endif
@endauth

        <hr class="my-3 border-gray-700">

        @auth
@if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('System Admin'))

<hr class="my-3">

<div class="menu-title">FINANCE</div>

<a href="/finance" class="menu-link">Finance</a>
<a href="{{ route('reports.payroll.summary') }}">Payroll Summary</a>
<a href="/accounts" class="menu-link">Chart of Accounts</a>
<a href="/reports" class="menu-link">Reports</a>
<a href="{{ route('reports.z') }}" class="menu-link">Z Report</a>
<li>
    <a href="{{ route('settings.index') }}">System Settings</a>
</li>
@endif
@endauth
        <a href="/suppliers" class="block hover:bg-gray-700 p-2 rounded">Suppliers</a>
       @auth
@if(auth()->user()->hasRole('System Admin'))

<hr class="my-3">

<div class="menu-title">SYSTEM</div>

<a href="/users" class="menu-link">Users</a>
<a href="/settings" class="menu-link">Company Settings</a>

@endif
@endauth

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

</body>
</html>
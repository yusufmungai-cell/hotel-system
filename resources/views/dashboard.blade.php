@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <h1 class="text-2xl font-bold">System Dashboard</h1>
{{-- HR APPROVAL WARNING --}}
@if($approvalStatus != 'approved')
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded shadow">
    <strong>Attention:</strong>
    Attendance for {{ $today->format('F Y') }} has not been approved by HR.
    Payroll generation is currently locked.
</div>
@endif
    {{-- ATTENDANCE APPROVAL WARNING --}}
    @if($approvalStatus == 'missing')
    <div class="bg-gray-100 border-l-4 border-gray-500 p-4 rounded">
        <h3 class="font-bold text-gray-700">Attendance Month Not Created</h3>
        <p>HR has not opened attendance for {{ $today->format('F Y') }} yet.</p>
    </div>
    @endif

    @if($approvalStatus == 'pending')
    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded">
        <h3 class="font-bold text-yellow-800">Payroll Locked</h3>
        <p>
            Attendance for <b>{{ $today->format('F Y') }}</b> is pending HR approval.
            Payroll cannot be generated.
        </p>

        <a href="{{ route('attendance.approval') }}"
           class="inline-block mt-3 bg-yellow-600 text-white px-4 py-2 rounded">
            Go to Attendance Approval
        </a>
    </div>
    @endif

    @if($approvalStatus == 'approved')
    <div class="bg-green-100 border-l-4 border-green-600 p-4 rounded">
        <h3 class="font-bold text-green-800">Ready For Payroll</h3>
        <p>
            Attendance for {{ $today->format('F Y') }} has been approved.
            Payroll generation allowed.
        </p>
    </div>
    @endif

{{-- HOTEL KPI SUMMARY --}}
<div class="grid grid-cols-4 gap-6 mb-8">

    {{-- TODAY RESTAURANT --}}
    <div class="bg-white rounded shadow p-5 border-l-4 border-green-600">
        <div class="text-gray-500 text-sm">Restaurant Sales Today</div>
        <div class="text-2xl font-bold text-green-700">
            KES {{ number_format($todayRestaurant,2) }}
        </div>
    </div>

    {{-- TODAY ROOMS --}}
    <div class="bg-white rounded shadow p-5 border-l-4 border-blue-600">
        <div class="text-gray-500 text-sm">Rooms Revenue Today</div>
        <div class="text-2xl font-bold text-blue-700">
            KES {{ number_format($todayRooms,2) }}
        </div>
    </div>

    {{-- TODAY PROFIT --}}
    <div class="bg-white rounded shadow p-5 border-l-4 border-purple-600">
        <div class="text-gray-500 text-sm">Estimated Profit Today</div>
        <div class="text-2xl font-bold text-purple-700">
            KES {{ number_format($todayProfit,2) }}
        </div>
    </div>

    {{-- OCCUPANCY --}}
    <div class="bg-white rounded shadow p-5 border-l-4 border-orange-500">
        <div class="text-gray-500 text-sm">Room Occupancy</div>
        <div class="text-2xl font-bold text-orange-600">
            {{ number_format($occupancyRate,1) }}%
        </div>
        <div class="text-xs text-gray-400">
            {{ $occupiedRooms }} / {{ $totalRooms }} rooms occupied
        </div>
    </div>

</div>
    {{-- QUICK MODULE ACCESS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-6">

        <a href="/pos" class="bg-blue-100 hover:bg-blue-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">POS</div>
            <div class="text-sm text-gray-600">Restaurant Sales</div>
        </a>

        <a href="/rooms" class="bg-purple-100 hover:bg-purple-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Rooms</div>
            <div class="text-sm text-gray-600">Bookings</div>
        </a>

        <a href="/attendance" class="bg-green-100 hover:bg-green-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Attendance</div>
            <div class="text-sm text-gray-600">Staff Clock In</div>
        </a>

        <a href="/payroll" class="bg-red-100 hover:bg-red-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Payroll</div>
            <div class="text-sm text-gray-600">Salary Processing</div>
        </a>

        <a href="/finance" class="bg-yellow-100 hover:bg-yellow-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Finance</div>
            <div class="text-sm text-gray-600">Profit & Loss</div>
        </a>

        <a href="/stock-dashboard" class="bg-indigo-100 hover:bg-indigo-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Stock</div>
            <div class="text-sm text-gray-600">Inventory Control</div>
        </a>

        <a href="/employees" class="bg-gray-100 hover:bg-gray-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Employees</div>
            <div class="text-sm text-gray-600">HR Management</div>
        </a>

        <a href="/settings" class="bg-pink-100 hover:bg-pink-200 p-6 rounded shadow text-center">
            <div class="text-xl font-bold">Settings</div>
            <div class="text-sm text-gray-600">Hotel Setup</div>
        </a>
		{{-- LIVE STAFF STATUS --}}
<div class="mt-10">

    <h2 class="text-xl font-bold mb-4">Live Staff Status (Today)</h2>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- INSIDE --}}
        <div class="bg-green-50 border border-green-300 rounded p-4">
            <h3 class="font-bold text-green-700 mb-3">🟢 Inside (Working)</h3>

            @forelse($inside as $emp)
                <div class="py-1">{{ $emp->name }}</div>
            @empty
                <div class="text-gray-500">No staff currently inside</div>
            @endforelse
        </div>

        {{-- LEFT --}}
        <div class="bg-yellow-50 border border-yellow-300 rounded p-4">
            <h3 class="font-bold text-yellow-700 mb-3">🟡 Left Already</h3>

            @forelse($left as $emp)
                <div class="py-1">{{ $emp->name }}</div>
            @empty
                <div class="text-gray-500">No one has left yet</div>
            @endforelse
        </div>

        {{-- ABSENT --}}
        <div class="bg-red-50 border border-red-300 rounded p-4">
            <h3 class="font-bold text-red-700 mb-3">🔴 Absent</h3>

            @forelse($absent as $emp)
                <div class="py-1">{{ $emp->name }}</div>
            @empty
                <div class="text-gray-500">All staff reported today</div>
            @endforelse
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="bg-white rounded shadow p-6 mt-8">
    <h3 class="text-lg font-bold mb-4">Monthly Income vs Expense</h3>
    <canvas id="financeChart"></canvas>
</div>

<script>
const financeData = @json($monthlyData);

const labels = financeData.map(d => d.month);
const income = financeData.map(d => d.income);
const expense = financeData.map(d => d.expense);

new Chart(document.getElementById('financeChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Income',
                data: income,
                borderColor: 'green',
                backgroundColor: 'rgba(34,197,94,0.2)',
                tension: 0.3
            },
            {
                label: 'Expense',
                data: expense,
                borderColor: 'red',
                backgroundColor: 'rgba(239,68,68,0.2)',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        }
    }
});
</script>
    </div>

</div>

@endsection
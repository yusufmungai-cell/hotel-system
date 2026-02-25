@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Payroll Management</h1>

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


<!-- GENERATE PAYROLL -->
<div class="bg-white shadow rounded p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Generate Payroll</h2>

    <form method="POST" action="{{ route('payroll.generate') }}" class="flex gap-4 items-end">
        @csrf

        <div>
            <label class="block text-sm font-medium">Select Month</label>
            <input type="month" name="month" required class="border p-2 rounded">
        </div>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
            Generate Payroll
        </button>
    </form>
</div>


<!-- PAYROLL HISTORY -->
<div class="bg-white shadow rounded p-6">

    <h2 class="text-lg font-semibold mb-4">Payroll History</h2>

    <table class="w-full border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Employee</th>
                <th class="p-3 text-left">Month</th>
                <th class="p-3 text-left">Gross</th>
                <th class="p-3 text-left">Deductions</th>
                <th class="p-3 text-left">Net</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Payslip</th>
            </tr>
        </thead>

        <tbody>
        @forelse($payrolls as $p)
        <tr class="border-t">

            <td class="p-3">{{ $p->employee->name ?? '-' }}</td>

            <td class="p-3">
                {{ \Carbon\Carbon::parse($p->payroll_month)->format('F Y') }}
            </td>

            <td class="p-3">KES {{ number_format($p->gross_pay,2) }}</td>
            <td class="p-3">KES {{ number_format($p->deductions,2) }}</td>
            <td class="p-3 font-bold">KES {{ number_format($p->net_pay,2) }}</td>

            <!-- STATUS BADGE -->
            <td class="p-3">
                @php
                    $month = \Carbon\Carbon::parse($p->payroll_month);
                    $attendanceMonth = \App\Models\AttendanceMonth::where('year',$month->year)
                        ->where('month',$month->month)
                        ->first();
                @endphp

                @if(!$attendanceMonth)
                    <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded">Unknown</span>

                @elseif(!$attendanceMonth->status || $attendanceMonth->status == 'open')
                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded">Open</span>

                @elseif($attendanceMonth->status == 'approved' && !$attendanceMonth->payroll_posted)
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded">Approved</span>

                @elseif($attendanceMonth->payroll_posted)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded">Posted</span>
                @endif
            </td>

            <td class="p-3">
                <a href="/payroll/payslip/{{ $p->id }}"
                   class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-black">
                    PDF
                </a>
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="7" class="p-4 text-center text-gray-500">
                No payroll generated yet
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection
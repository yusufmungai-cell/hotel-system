@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Payroll Summary Report</h2>

<!-- MONTH SELECTOR -->
<form method="GET" class="mb-6">
    <label class="font-semibold">Select Payroll Month</label>
    <select name="month" onchange="this.form.submit()"
        class="border rounded px-4 py-2 ml-3">

        @foreach($months as $m)
            <option value="{{ $m }}"
                {{ $selectedMonth==$m ? 'selected':'' }}>
                {{ \Carbon\Carbon::parse($m.'-01')->format('F Y') }}
            </option>
        @endforeach

    </select>
</form>
<!-- DASHBOARD CARDS -->
<div class="grid grid-cols-4 gap-6 mb-8">

    <div class="bg-white shadow rounded p-5">
        <div class="text-gray-500">Employees Paid</div>
        <div class="text-2xl font-bold">{{ $summary['employees'] }}</div>
    </div>

    <div class="bg-white shadow rounded p-5">
        <div class="text-gray-500">Total Gross</div>
        <div class="text-2xl font-bold text-blue-600">
            KES {{ number_format($summary['gross'],2) }}
        </div>
    </div>

    <div class="bg-white shadow rounded p-5">
        <div class="text-gray-500">Total Deductions</div>
        <div class="text-2xl font-bold text-red-600">
            KES {{ number_format($summary['deductions'],2) }}
        </div>
    </div>

    <div class="bg-white shadow rounded p-5">
        <div class="text-gray-500">Net Salaries Paid</div>
        <div class="text-2xl font-bold text-green-600">
            KES {{ number_format($summary['net'],2) }}
        </div>
    </div>

</div>
<!-- CHART -->
<div class="bg-white shadow rounded p-6 mb-10">
    <canvas id="payrollChart" height="90"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('payrollChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Gross Pay','Deductions','Net Pay'],
        datasets: [{
            label: 'Payroll Overview',
            data: [
                {{ $summary['gross'] }},
                {{ $summary['deductions'] }},
                {{ $summary['net'] }}
            ],
            backgroundColor: [
                '#3b82f6',
                '#ef4444',
                '#10b981'
            ]
        }]
    }
});
</script>
@if($payrolls->count())

<table class="w-full border shadow bg-white">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Employee</th>
            <th class="p-3 text-left">Gross</th>
            <th class="p-3 text-left">NSSF</th>
            <th class="p-3 text-left">NHIF</th>
            <th class="p-3 text-left">PAYE</th>
            <th class="p-3 text-left">Loan</th>
            <th class="p-3 text-left">Total Deductions</th>
            <th class="p-3 text-left">Net Pay</th>
        </tr>
    </thead>

    <tbody>
        @php
            $grossTotal=0;
            $dedTotal=0;
            $netTotal=0;
        @endphp

        @foreach($payrolls as $p)

        @php
            $grossTotal += $p->gross_pay;
            $dedTotal += $p->deductions;
            $netTotal += $p->net_pay;
        @endphp

        <tr class="border-t">
            <td class="p-3">{{ $p->employee->name }}</td>
            <td class="p-3">{{ number_format($p->gross_pay,2) }}</td>
            <td class="p-3">{{ number_format($p->nssf,2) }}</td>
            <td class="p-3">{{ number_format($p->nhif,2) }}</td>
            <td class="p-3">{{ number_format($p->paye,2) }}</td>
            <td class="p-3">{{ number_format($p->loan_deduction,2) }}</td>
            <td class="p-3 font-semibold">{{ number_format($p->deductions,2) }}</td>
            <td class="p-3 font-bold">{{ number_format($p->net_pay,2) }}</td>
        </tr>

        @endforeach

        <!-- TOTAL ROW -->
        <tr class="bg-gray-100 font-bold border-t-2">
            <td class="p-3">TOTAL</td>
            <td class="p-3">{{ number_format($grossTotal,2) }}</td>
            <td class="p-3"></td>
            <td class="p-3"></td>
            <td class="p-3"></td>
            <td class="p-3"></td>
            <td class="p-3">{{ number_format($dedTotal,2) }}</td>
            <td class="p-3">{{ number_format($netTotal,2) }}</td>
        </tr>

    </tbody>
</table>

@else
<div class="text-gray-500">No payroll found for this month</div>
@endif

@endsection
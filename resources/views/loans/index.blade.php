@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Employee Loans</h1>

@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<!-- ISSUE LOAN -->
<div class="bg-white p-5 rounded shadow mb-6">
    <form method="POST" action="{{ route('loans.store') }}" class="grid grid-cols-4 gap-4">
        @csrf

        <select name="employee_id" class="border p-2 rounded" required>
            <option value="">Select Employee</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
            @endforeach
        </select>

        <input type="number" step="0.01" name="amount" placeholder="Loan Amount" class="border p-2 rounded" required>

        <input type="number" step="0.01" name="monthly_deduction" placeholder="Monthly Deduction" class="border p-2 rounded" required>

        <button class="bg-blue-600 text-white rounded px-4">
            Issue Loan
        </button>
    </form>
</div>

<!-- LOANS TABLE -->
<table class="w-full bg-white shadow rounded">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Employee</th>
            <th class="p-3">Amount</th>
            <th class="p-3">Balance</th>
            <th class="p-3">Monthly Deduction</th>
        </tr>
    </thead>

    <tbody>
    @foreach($loans as $loan)
        <tr class="border-t">
            <td class="p-3">{{ $loan->employee->name }}</td>
            <td class="p-3">{{ number_format($loan->amount,2) }}</td>
            <td class="p-3 font-bold">{{ number_format($loan->balance,2) }}</td>
            <td class="p-3">{{ number_format($loan->monthly_deduction,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection
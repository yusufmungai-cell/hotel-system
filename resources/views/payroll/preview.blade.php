@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">
Payroll Preview — {{ $month->format('F Y') }}
</h2>

<table border="1" cellpadding="6">
<tr>
    <th>Employee</th>
    <th>Days</th>
    <th>Gross</th>
    <th>Deductions</th>
    <th>Net Pay</th>
</tr>

@foreach($preview as $row)
<tr>
    <td>{{ $row['employee'] }}</td>
    <td>{{ $row['days'] }}</td>
    <td>{{ number_format($row['gross'],2) }}</td>
    <td>{{ number_format($row['deductions'],2) }}</td>
    <td><b>{{ number_format($row['net'],2) }}</b></td>
</tr>
@endforeach
</table>

<br>

<hr class="my-4">

<form method="POST" action="{{ route('payroll.post') }}">
    @csrf

    <input type="hidden" name="month" value="{{ $month->format('Y-m') }}">

    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded font-bold">
        POST PAYROLL
    </button>
</form>

@endsection

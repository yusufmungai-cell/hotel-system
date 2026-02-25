@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="p-3 bg-green-100 text-green-700 mb-3">
{{ session('success') }}
</div>
@endif

<form method="POST">
@csrf

<select name="employee_id" class="border p-2 mb-2">
@foreach($employees as $e)
<option value="{{ $e->id }}">{{ $e->name }}</option>
@endforeach
</select>

<input type="month" name="salary_month" class="border p-2 mb-2">
<input name="amount" placeholder="Amount" class="border p-2 mb-2">

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Save Salary
</button>

</form>

<hr class="my-4">

@foreach($salaries as $s)
<div class="border p-3 mb-2">
{{ $s->employee->name }} |
KES {{ number_format($s->amount,2) }} |
{{ $s->salary_month }}
</div>
@endforeach

@endsection

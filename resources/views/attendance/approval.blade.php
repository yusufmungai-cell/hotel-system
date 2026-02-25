@extends('layouts.app')

@section('content')
<h2>Attendance Monthly Approval</h2>
@if(session('success'))
<div class="bg-green-200 text-green-900 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-200 text-red-900 p-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif
<form method="GET">
    <input type="month" name="month" value="{{ $month->format('Y-m') }}">
    <button>Load</button>
</form>

<hr>

<form method="POST" action="{{ route('attendance.approval.update') }}">
@csrf

<table border="1" cellpadding="6">
<tr>
    <th>Employee</th>
    <th>Date</th>
    <th>Status</th>
    <th>Overtime</th>
</tr>

@foreach($employees as $employee)
@foreach($employee->attendances as $att) <tr> <td>{{ $employee->name }}</td> <td>{{ $att->date }}</td>

```
    <td>
        <select name="attendance[{{$att->id}}][status]">
            <option value="present" {{ $att->status=='present'?'selected':'' }}>Present</option>
            <option value="halfday" {{ $att->status=='halfday'?'selected':'' }}>Half Day</option>
            <option value="absent" {{ $att->status=='absent'?'selected':'' }}>Absent</option>
            <option value="offday" {{ $att->status=='offday'?'selected':'' }}>Off Day</option>
            <option value="duty" {{ $att->status=='duty'?'selected':'' }}>Official Duty</option>
        </select>
    </td>

    <td>
        <input type="number" step="0.1" name="attendance[{{$att->id}}][overtime_hours]" value="{{ $att->overtime_hours }}">
    </td>
</tr>
@endforeach
```

@endforeach

</table>

<br>
<button type="submit">Save Changes</button>
</form>

<hr>
<h2 class="text-xl font-bold mt-6 mb-2">Monthly Summary</h2>

<table border="1" cellpadding="6" class="mb-6">
<tr>
    <th>Employee</th>
    <th>Present</th>
    <th>Half Day</th>
    <th>Absent</th>
    <th>Duty</th>
    <th>Off Day</th>
    <th>Overtime Hrs</th>
</tr>

@foreach($summary as $row)
<tr>
    <td>{{ $row['employee'] }}</td>
    <td>{{ $row['present'] }}</td>
    <td>{{ $row['halfday'] }}</td>
    <td>{{ $row['absent'] }}</td>
    <td>{{ $row['duty'] }}</td>
    <td>{{ $row['offday'] }}</td>
    <td>{{ $row['overtime'] }}</td>
</tr>
@endforeach
</table>
<div style="background:#ffe9b3;padding:12px;margin-bottom:10px;">
<b>Important:</b> Once approved:
<ul>
<li>Attendance will be locked</li>
<li>Payroll will use this data</li>
<li>No further edits allowed</li>
</ul>
</div>

<form method="POST" action="{{ route('attendance.approval.approve') }}">
@csrf
<input type="hidden" name="month" value="{{ $month->format('Y-m') }}">
<button type="submit">APPROVE ENTIRE MONTH</button>
<a href="{{ route('payroll.preview',['month'=>$month->format('Y-m')]) }}"
style="background:blue;color:white;padding:10px;display:inline-block;margin-top:10px;">
Preview Payroll
</a>

</form>
@endsection
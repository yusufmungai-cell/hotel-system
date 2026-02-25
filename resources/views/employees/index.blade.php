@extends('layouts.app')

@section('content')

<a href="/employees/create" class="btn btn-primary">Add Employee</a>

<table class="table mt-4">
<tr>
<th>Staff No</th>
<th>Name</th>
<th>Department</th>
</tr>

@foreach($employees as $emp)
<tr>
<td>{{ $emp->staff_no }}</td>
<td>{{ $emp->name }}</td>
<td>{{ $emp->department }}</td>
</tr>
@endforeach

</table>

@endsection

@extends('layouts.app')

@section('content')

<form method="POST" action="/employees">
@csrf

<input name="staff_no" placeholder="Staff No" class="form-control mb-2">
<input name="name" placeholder="Name" class="form-control mb-2">
<label>Department</label>
<select name="department_id" class="border rounded p-2 w-full">
    <option value="">-- Select Department --</option>
    @foreach($departments as $dept)
        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
    @endforeach
</select>

<label>Position</label>
<select name="position_id" class="border rounded p-2 w-full">
    <option value="">-- Select Position --</option>
    @foreach($positions as $pos)
        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
    @endforeach
</select>
<label>Employment Type</label>
<select name="employment_type_id" class="border rounded p-2 w-full" required>
    @foreach($employmentTypes as $type)
        <option value="{{ $type->id }}">{{ $type->name }}</option>
    @endforeach
</select>

<input name="phone" placeholder="Phone" class="form-control mb-2">

<button class="btn btn-success">Save</button>

</form>

@endsection

@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="bg-green-100 p-3 mb-4">{{ session('success') }}</div>
@endif

<table class="table w-full border">
<tr>
<th>ID</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

@foreach($requests as $req)
<tr>
<td>{{ $req->id }}</td>
<td>{{ $req->created_at }}</td>
<td>{{ $req->status }}</td>
<td>
<a href="/storekeeper/request/{{ $req->id }}" class="btn btn-primary">Open</a>
</td>
</tr>
@endforeach

</table>

@endsection

@extends('layouts.app')

@section('content')

<table class="table w-full border">
<tr>
<th>Date</th>
<th>Ingredient</th>
<th>Type</th>
<th>Qty</th>
<th>Before</th>
<th>After</th>
<th>Reference</th>
<th>User</th>
</tr>

@foreach($movements as $m)
<tr>
<td>{{ $m->created_at }}</td>
<td>{{ $m->ingredient->name }}</td>
<td>{{ $m->type }}</td>
<td>{{ $m->qty }}</td>
<td>{{ $m->before_qty }}</td>
<td>{{ $m->after_qty }}</td>
<td>{{ $m->reference }}</td>
<td>{{ $m->user->name ?? '' }}</td>
</tr>
@endforeach

</table>

@endsection

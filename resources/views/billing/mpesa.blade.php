@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Confirm M-Pesa Payment</h1>

<div class="bg-white shadow rounded p-6 max-w-lg">

    <div class="mb-4">
        <div class="text-lg font-semibold">Order #{{ $order->id }}</div>
        <div class="text-gray-600">Amount: KSh {{ number_format($order->total,2) }}</div>
    </div>

    <form method="POST" action="{{ route('billing.mpesa.confirm',$order->id) }}">
        @csrf

        <label class="block mb-2 font-semibold">Enter M-Pesa Code</label>
        <input type="text" name="code" required
               class="w-full border rounded p-3 mb-4 text-lg uppercase">

        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
            Confirm Payment
        </button>
    </form>

</div>

@endsection
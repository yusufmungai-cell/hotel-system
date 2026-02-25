<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Receipt</title>

<style>
body{
    font-family: monospace;
    width: 280px;
    margin: auto;
    color:#000;
}

.center{text-align:center;}
.right{text-align:right;}
.bold{font-weight:bold;}
hr{border-top:1px dashed #000;margin:6px 0;}

@media print{
    body{width:280px;}
    button{display:none;}
}
</style>
</head>

<body>

<div class="center bold">YOUR RESTAURANT NAME</div>
<div class="center">Thank you for dining</div>
<hr>

<div>Order #: {{ $order->id }}</div>
<div>Waiter: {{ $order->waiter->name ?? '-' }}</div>
<div>Date: {{ now()->format('d/m/Y H:i') }}</div>

<hr>

@foreach($order->items as $item)
<div style="display:flex;justify-content:space-between;">
    <span>{{ $item->menuItem->name }} x{{ $item->qty }}</span>
    <span>{{ number_format($item->total,2) }}</span>
</div>
@endforeach

<hr>

<div style="display:flex;justify-content:space-between;" class="bold">
    <span>TOTAL</span>
    <span>KSh {{ number_format($order->total,2) }}</span>
</div>

<div>Payment: {{ strtoupper($order->payment_method) }}</div>

<hr>

<div class="center">**** SERVED ****</div>
<div class="center">Karibu Tena 😊</div>

<button onclick="window.print()">PRINT RECEIPT</button>

<script>
window.onload = () => setTimeout(()=>window.print(),500);
</script>
<script>
window.onload = function() {
    window.print();
};
</script>
<script>
window.onafterprint = function(){
    window.location.href = "{{ route('billing.index') }}";
};
</script>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: DejaVu Sans, sans-serif; }
.box { border:1px solid #000;padding:15px;width:700px;margin:auto;}
h2{text-align:center;margin-bottom:5px;}
.table{width:100%;border-collapse:collapse;margin-top:15px;}
.table td,.table th{border:1px solid #000;padding:6px;text-align:left;}
.right{text-align:right;}
.bold{font-weight:bold;}
.section{background:#f0f0f0;font-weight:bold;}
</style>
</head>
<body>

<div class="box">

<h2>{{ config('app.name') }}</h2>
<p style="text-align:center;">Employee Payslip</p>

<hr>

<p><b>Employee:</b> {{ $payroll->employee->name }}</p>
<p><b>Month:</b> {{ $month->format('F Y') }}</p>

<table class="table">

<tr class="section">
    <td colspan="2">EARNINGS</td>
</tr>
<tr>
    <td>Gross Pay</td>
    <td class="right">{{ number_format($payroll->gross_pay,2) }}</td>
</tr>

<tr class="section">
    <td colspan="2">DEDUCTIONS</td>
</tr>
<tr>
    <td>NSSF</td>
    <td class="right">{{ number_format($payroll->nssf,2) }}</td>
</tr>
<tr>
    <td>NHIF</td>
    <td class="right">{{ number_format($payroll->nhif,2) }}</td>
</tr>
<tr>
    <td>PAYE</td>
    <td class="right">{{ number_format($payroll->paye,2) }}</td>
</tr>
<tr>
    <td>Loan Deduction</td>
    <td class="right">{{ number_format($payroll->loan_deduction,2) }}</td>
</tr>

<tr class="bold">
    <td>Total Deductions</td>
    <td class="right">{{ number_format($payroll->deductions,2) }}</td>
</tr>

<tr class="section">
    <td>NET PAY</td>
    <td class="right bold">{{ number_format($payroll->net_pay,2) }}</td>
</tr>

</table>

<br><br>
<p>Generated on {{ now()->format('d M Y H:i') }}</p>

</div>
</body>
</html>
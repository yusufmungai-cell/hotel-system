<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Carbon\Carbon;

class PayrollReportController extends Controller
{
   public function index()
{
    $months = Payroll::selectRaw('DATE_FORMAT(payroll_month,"%Y-%m") as month')
        ->distinct()
        ->orderBy('month','desc')
        ->pluck('month');

    $selectedMonth = request('month') ?? $months->first();

    $payrolls = collect();
    $summary = [
        'gross' => 0,
        'deductions' => 0,
        'net' => 0,
        'employees' => 0
    ];

    if($selectedMonth){
        $payrolls = Payroll::with('employee')
            ->whereYear('payroll_month', Carbon::parse($selectedMonth)->year)
            ->whereMonth('payroll_month', Carbon::parse($selectedMonth)->month)
            ->get();

        $summary['gross'] = $payrolls->sum('gross_pay');
        $summary['deductions'] = $payrolls->sum('deductions');
        $summary['net'] = $payrolls->sum('net_pay');
        $summary['employees'] = $payrolls->count();
    }

    return view('reports.payroll-summary', compact('months','payrolls','selectedMonth','summary'));
}
}
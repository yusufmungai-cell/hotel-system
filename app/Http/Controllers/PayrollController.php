<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\AttendanceMonth;
use App\Models\Loan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class PayrollController extends Controller
{

/*=====================================================
 PAYROLL HISTORY PAGE
=====================================================*/
public function index()
{
    $payrolls = Payroll::with('employee')->latest()->get();
    return view('payroll.index', compact('payrolls'));
}


/*=====================================================
 STATUTORY CALCULATOR
=====================================================*/
private function statutory($gross)
{
    if($gross <= 0){
        return ['nssf'=>0,'nhif'=>0,'paye'=>0,'total'=>0];
    }

    $nssf = min($gross * 0.06, 1080);

    if ($gross <= 5999) $nhif = 150;
    elseif ($gross <= 7999) $nhif = 300;
    elseif ($gross <= 11999) $nhif = 400;
    elseif ($gross <= 14999) $nhif = 500;
    else $nhif = 600;

    $taxable = $gross - $nssf;
    $paye = max(($taxable * 0.1) - 2400, 0);

    return [
        'nssf'=>round($nssf,2),
        'nhif'=>round($nhif,2),
        'paye'=>round($paye,2),
        'total'=>round($nssf+$nhif+$paye,2)
    ];
}


/*=====================================================
 PREVIEW PAYROLL
=====================================================*/
public function preview(Request $request)
{
    $monthString = $request->month ?? now()->format('Y-m');
    $month = Carbon::parse($monthString.'-01');

    $employees = Employee::all();
    $preview = [];

    foreach ($employees as $employee){

        $daysWorked = Attendance::where('employee_id',$employee->id)
            ->whereYear('date',$month->year)
            ->whereMonth('date',$month->month)
            ->where('is_approved',1)
            ->sum(DB::raw("
                CASE
                    WHEN status='present' THEN 1
                    WHEN status='halfday' THEN 0.5
                    ELSE 0
                END
            "));

        // Permanent employees full salary
        if(optional($employee->employmentType)->name == 'casual'){
            $gross = $daysWorked * ($employee->daily_rate ?? 0);
        }else{
            $gross = $employee->monthly_salary ?? $employee->salary ?? 0;
        }

        // Loan preview only (no deduction yet)
        $loanDeduction = 0;
        $loan = Loan::where('employee_id',$employee->id)
                    ->where('balance','>',0)
                    ->first();

        if($loan){
            $loanDeduction = min($loan->monthly_deduction,$loan->balance);
        }

        $stat = $this->statutory($gross);

        $deductions = $stat['total'] + $loanDeduction;
        $net = $gross - $deductions;

        $preview[]=[
            'employee'=>$employee->name,
            'days'=>$daysWorked,
            'gross'=>$gross,
            'deductions'=>$deductions,
            'net'=>$net
        ];
    }

    return view('payroll.preview',compact('preview','month'));
}


/*=====================================================
 POST PAYROLL
=====================================================*/
public function post(Request $request)
{
    $month = Carbon::parse($request->month);
    $year = $month->year;
    $monthNumber = $month->month;

    $attendanceMonth = AttendanceMonth::firstOrCreate(
        ['year'=>$year,'month'=>$monthNumber],
        ['status'=>'open']
    );

    if($attendanceMonth->status != 'approved'){
        return redirect()
            ->route('attendance.approval',['month'=>$month->format('Y-m')])
            ->with('error','Please approve attendance for this month before posting payroll');
    }

    $employees = Employee::all();

    foreach ($employees as $employee) {

        $daysWorked = Attendance::where('employee_id',$employee->id)
            ->whereYear('date',$year)
            ->whereMonth('date',$monthNumber)
            ->where('is_approved',1)
            ->sum(DB::raw("
                CASE
                    WHEN status = 'present' THEN 1
                    WHEN status = 'halfday' THEN 0.5
                    ELSE 0
                END
            "));

        if(optional($employee->employmentType)->name == 'casual'){
            $gross = $daysWorked * ($employee->daily_rate ?? 0);
        }else{
            $gross = $employee->monthly_salary ?? $employee->salary ?? 0;
        }

        $stat = $this->statutory($gross);

        // LOAN DEDUCTION (real deduction now)
        $loanDeduction = 0;
        $loan = Loan::where('employee_id',$employee->id)
            ->where('balance','>',0)
            ->first();

        if($loan){
            $loanDeduction = min($loan->monthly_deduction, $loan->balance);
            $loan->balance -= $loanDeduction;
            if($loan->balance <= 0) $loan->balance = 0;
            $loan->save();
        }

        $totalDeductions = $stat['total'] + $loanDeduction;
        $net = $gross - $totalDeductions;

        Payroll::updateOrCreate(
            [
                'employee_id'=>$employee->id,
                'payroll_month'=>$month->format('Y-m-01')
            ],
            [
                'days_worked'=>$daysWorked,
                'gross_pay'=>round($gross,2),
                'nssf'=>$stat['nssf'],
                'nhif'=>$stat['nhif'],
                'paye'=>$stat['paye'],
                'loan_deduction'=>round($loanDeduction,2),
                'deductions'=>round($totalDeductions,2),
                'net_pay'=>round($net,2),
                'status'=>'posted'
            ]
        );
    }

    $attendanceMonth->payroll_posted = 1;
    $attendanceMonth->save();

    return redirect()->route('payroll.index')
        ->with('success','Payroll posted successfully');
}


/*=====================================================
 PAYSLIP PDF
=====================================================*/
public function payslip($id)
{
    $payroll = Payroll::with('employee')->findOrFail($id);
    $month = Carbon::parse($payroll->payroll_month);

    $pdf = Pdf::loadView('payroll.payslip',[
        'payroll'=>$payroll,
        'month'=>$month
    ]);

    return $pdf->stream('Payslip_'.$payroll->employee->name.'.pdf');
}


/*=====================================================
 GENERATE BUTTON
=====================================================*/
public function generate(Request $request)
{
    return redirect()->route('payroll.preview',['month'=>$request->month]);
}

}
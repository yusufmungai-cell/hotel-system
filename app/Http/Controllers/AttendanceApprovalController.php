<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceMonth;
use Carbon\Carbon;
use DB;

class AttendanceApprovalController extends Controller
{
    public function index(Request $request)
{
    $month = $request->month
        ? Carbon::parse($request->month)->startOfMonth()
        : now()->startOfMonth();

    $year = $month->year;
    $monthNumber = $month->month;

    $employees = Employee::with(['attendances' => function($q) use ($year,$monthNumber){
        $q->whereYear('date',$year)
          ->whereMonth('date',$monthNumber);
    }])->get();

    // ===== BUILD SUMMARY =====
    $summary = [];

    foreach ($employees as $employee) {

        $present = 0;
        $halfday = 0;
        $absent = 0;
        $offday = 0;
        $duty = 0;
        $overtime = 0;

        foreach ($employee->attendances as $att) {

            switch($att->status) {
                case 'present': $present++; break;
                case 'halfday': $halfday++; break;
                case 'absent': $absent++; break;
                case 'offday': $offday++; break;
                case 'duty': $duty++; break;
            }

            $overtime += $att->overtime_hours ?? 0;
        }

        $summary[] = [
            'employee' => $employee->name,
            'present' => $present,
            'halfday' => $halfday,
            'absent' => $absent,
            'offday' => $offday,
            'duty' => $duty,
            'overtime' => round($overtime,2)
        ];
    }

    return view('attendance.approval', compact('employees','month','summary'));
}

    public function update(Request $request)
    {
        foreach ($request->attendance as $id => $data) {
            Attendance::where('id',$id)->update([
                'status' => $data['status'],
                'overtime_hours' => $data['overtime_hours'] ?? 0,
                'is_approved' => 1
            ]);
        }

        return back()->with('success','Attendance updated successfully');
    }

   public function approve(Request $request)
{
    $month = \Carbon\Carbon::parse($request->month);

    // Create month record if missing
    $attendanceMonth = \App\Models\AttendanceMonth::firstOrCreate([
        'year'  => $month->year,
        'month' => $month->month
    ]);

    // Mark all rows approved
    \App\Models\Attendance::whereYear('date', $month->year)
        ->whereMonth('date', $month->month)
        ->update(['is_approved' => 1]);

    // Mark month approved
    $attendanceMonth->status = 'approved';
    $attendanceMonth->payroll_posted = 0;
    $attendanceMonth->save();

    return redirect()->back()->with('success','Month approved successfully. You can now generate payroll.');
}
}

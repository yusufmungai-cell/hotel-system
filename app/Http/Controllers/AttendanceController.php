<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Services\AttendanceLockService;

class AttendanceController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if (!$user->employee) {
        return redirect('/dashboard')
            ->with('error','System administrators do not use attendance.');
    }

    $employee = $user->employee;

    $todayAttendance = Attendance::where('employee_id', $employee->id)
        ->whereDate('date', now()->toDateString())
        ->first();

    $attendances = Attendance::where('employee_id', $employee->id)
        ->latest('date')
        ->take(10)
        ->get();

    return view('attendance.index', compact('todayAttendance','attendances'));
}

    // CLOCK IN
   public function clockIn()
{
    $employee = auth()->user()->employee;
    $today = now()->toDateString();

    if (AttendanceLockService::isLocked($today)) {
        return back()->with('error','Attendance locked for this month');
    }

    // find today's attendance
    $attendance = Attendance::where('employee_id',$employee->id)
        ->whereDate('date',$today)
        ->first();

    // if exists and still open shift
    if($attendance && !$attendance->time_out){
        return back()->with('error','You already clocked in');
    }

    // if exists but closed → reopen new shift
    if($attendance && $attendance->time_out){
        $attendance->time_in = now();
        $attendance->time_out = null;
        $attendance->worked_hours = null;
        $attendance->save();

        return back()->with('success','New shift started');
    }

    // create first attendance of the day
    Attendance::create([
        'employee_id'=>$employee->id,
        'date'=>$today,
        'time_in'=>now()
    ]);

    return back()->with('success','Clock-in successful');
}

    // CLOCK OUT
    public function clockOut()
    {
        $employee = auth()->user()->employee;
        $today = now()->toDateString();

        if (AttendanceLockService::isLocked($today)) {
            return back()->with('error','Attendance locked for this month');
        }

        $attendance = Attendance::where('employee_id',$employee->id)
            ->whereDate('date',$today)
            ->first();

        if(!$attendance){
            return back()->with('error','You have not clocked in');
        }

        if($attendance->time_out){
            return back()->with('error','Already clocked out');
        }

        $attendance->time_out = now();

        $timeIn = \Carbon\Carbon::parse($attendance->time_in);
$timeOut = now();

// Handle overnight shifts (after midnight)
if ($timeOut->lessThan($timeIn)) {
    $timeOut->addDay();
}

$workedMinutes = $timeIn->diffInMinutes($timeOut);
$workedHours = round($workedMinutes / 60, 2);

// accumulate hours instead of overwrite
$attendance->worked_hours = ($attendance->worked_hours ?? 0) + $workedHours;

$attendance->time_out = $timeOut;
$attendance->save();

        return back()->with('success','Clock-out successful');
    }
}
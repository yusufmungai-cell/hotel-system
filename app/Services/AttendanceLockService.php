<?php

namespace App\Services;

use App\Models\AttendanceMonth;
use Carbon\Carbon;

class AttendanceLockService
{
    public static function isLocked($date)
    {
        $date = Carbon::parse($date);

        return AttendanceMonth::where('year',$date->year)
            ->where('month',$date->month)
            ->where('status','approved')
            ->exists();
    }
}

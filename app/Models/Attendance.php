<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
    'employee_id',
    'date',
    'time_in',
    'time_out',
    'worked_hours',
    'status',
    'suggested_status',
    'overtime_hours',
    'suggested_overtime_hours',
    'late_minutes',
    'is_full_day',
    'is_approved'
];


    protected $casts = [
    'date' => 'date',
    'time_in' => 'datetime',
    'time_out' => 'datetime',
    'is_full_day' => 'boolean',
    'is_approved' => 'boolean',
];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
	public function approver()
{
    return $this->belongsTo(User::class, 'approved_by');
}

}

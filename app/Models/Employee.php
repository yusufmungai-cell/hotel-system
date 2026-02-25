<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
    'staff_no',
    'name',
    'department_id',
    'position_id',
    'employment_type_id',
    'daily_rate',
    'monthly_salary',
    'overtime_rate',
    'min_hours_per_day',
    'leave_days',
    'phone'
];


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
	public function department()
{
    return $this->belongsTo(Department::class);
}

public function position()
{
    return $this->belongsTo(Position::class);
}

public function employmentType()
{
    return $this->belongsTo(EmploymentType::class);
}
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}
public function loans()
{
    return $this->hasMany(\App\Models\Loan::class);
}
}

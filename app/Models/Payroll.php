<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_month',
        'days_worked',
        'gross_pay',
        'nssf',
        'nhif',
        'paye',
        'loan_deduction',
        'deductions',
        'net_pay',
        'status'
    ];

    protected $casts = [
        'payroll_month' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
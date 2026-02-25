<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceMonth extends Model
{
    protected $fillable = [
        'year',
        'month',
        'status',
        'approved_by',
        'approved_at'
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

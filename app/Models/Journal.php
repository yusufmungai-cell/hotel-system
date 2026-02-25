<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'journal_date',
        'description'
    ];

    protected $casts = [
        'journal_date' => 'date',
    ];

    // ==========================
    // RELATIONSHIPS
    // ==========================

    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'department',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(ProductionRequestItem::class);
    }
}

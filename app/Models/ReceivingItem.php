<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingItem extends Model
{
    use HasFactory;

    protected $fillable = [
    'receiving_id',
    'ingredient_id',
    'qty',
    'price',
    'total'
];

public function ingredient()
{
    return $this->belongsTo(\App\Models\Ingredient::class);
}

}

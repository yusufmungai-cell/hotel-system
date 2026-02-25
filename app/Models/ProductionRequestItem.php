<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_request_id',
        'ingredient_id',
        'qty'
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function productionRequest()
    {
        return $this->belongsTo(ProductionRequest::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['menu_item_id'];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }
}

{
    //
}

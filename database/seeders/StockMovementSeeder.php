<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Ingredient;
use Carbon\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        StockMovement::truncate();

        $ingredients = Ingredient::all();

        if ($ingredients->count() == 0) {
            return;
        }

        for ($i = 0; $i < 100; $i++) {

            $ingredient = $ingredients->random();

            StockMovement::create([
                'ingredient_id' => $ingredient->id,
                'qty' => rand(1, 10),
                'type' => 'ISSUE',
                'created_at' => Carbon::now()->subDays(rand(0, 90))
            ]);
        }
    }
}

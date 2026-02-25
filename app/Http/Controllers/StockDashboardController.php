<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockMovement;

class StockDashboardController extends Controller
{
   public function index()
{
    $totalIngredients = \App\Models\Ingredient::count();
    $lowStock = \App\Models\Ingredient::where('stock','<',10)->count();
    $totalStock = \App\Models\Ingredient::sum('stock');
    $totalValue = \App\Models\Ingredient::sum(\DB::raw('stock * cost_price'));

    return view('stock.dashboard', compact(
        'totalIngredients',
        'lowStock',
        'totalStock',
        'totalValue'
    ));
}

}

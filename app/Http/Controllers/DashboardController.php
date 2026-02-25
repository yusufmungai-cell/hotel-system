<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\Ingredient;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /* ========================
           INCOME
        ======================== */

        $restaurantIncome = Order::where('status', 'closed')->sum('total');
        $roomIncome = Booking::where('status', 'checked_out')->sum('total');
        $totalIncome = $restaurantIncome + $roomIncome;

        /* ========================
           EXPENSE
        ======================== */

        $ingredientExpense = StockMovement::where('type', 'ISSUE')
            ->join('ingredients', 'stock_movements.ingredient_id', '=', 'ingredients.id')
            ->sum(DB::raw('stock_movements.qty * ingredients.cost_price'));

        $totalExpense = $ingredientExpense;

        $netProfit = $totalIncome - $totalExpense;

        /* ========================
           TODAY
        ======================== */

        $todayIncome = Order::where('status', 'closed')
            ->whereDate('created_at', today())
            ->sum('total')
            +
            Booking::where('status', 'checked_out')
            ->whereDate('updated_at', today())
            ->sum('total');

        /* ========================
           STOCK
        ======================== */

        $totalStockItems = Ingredient::count();
        $lowStock = Ingredient::where('stock', '<', 10)->get();

        $stockValue = Ingredient::sum(DB::raw('stock * cost_price'));

        /* ========================
           SUPPLIERS
        ======================== */

        $supplierPayable = Supplier::sum('balance');

        return view('dashboard.index', compact(
            'totalIncome',
            'totalExpense',
            'netProfit',
            'todayIncome',
            'totalStockItems',
            'lowStock',
            'stockValue',
            'supplierPayable'
        ));
    }
}

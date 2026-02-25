<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use App\Models\Salary;


class FinanceController extends Controller
{
   public function index(Request $request)
{
    $selectedMonth = $request->month ?? date('Y-m');

    $year = substr($selectedMonth, 0, 4);
    $month = substr($selectedMonth, 5, 2);

    /* =======================
       TOTAL (ALL TIME)
    ======================= */

    $restaurantSales = Order::where('status', 'closed')->sum('total');

    $roomRevenue = Booking::where('status', 'checked_out')->sum('total');

    $totalIncome = $restaurantSales + $roomRevenue;

    $ingredientExpense = StockMovement::where('type', 'ISSUE')
        ->join('ingredients', 'stock_movements.ingredient_id', '=', 'ingredients.id')
        ->sum(\DB::raw('stock_movements.qty * ingredients.cost_price'));

    $salaryExpense = Salary::sum('amount');

    $totalExpense = $ingredientExpense;


    $netProfit = $totalIncome - $totalExpense;

    $profitMargin = $totalIncome > 0 
        ? ($netProfit / $totalIncome) * 100 
        : 0;
    // Salary Expense (ALL TIME)
    $salaryExpense = 0;

    /* =======================
       MONTHLY
    ======================= */

    $monthlyRestaurant = Order::where('status', 'closed')
    ->whereYear('created_at', $year)
    ->whereMonth('created_at', $month)
    ->sum('total');
    $monthlyRooms = Booking::where('status', 'checked_out')
    ->whereYear('updated_at', $year)
    ->whereMonth('updated_at', $month)
    ->sum('total');

    $monthlyIncome = $monthlyRestaurant + $monthlyRooms;

    $monthlyExpense = StockMovement::where('type', 'ISSUE')
    ->whereYear('stock_movements.created_at', $year)
    ->whereMonth('stock_movements.created_at', $month)
    ->join('ingredients', 'stock_movements.ingredient_id', '=', 'ingredients.id')
    ->sum(DB::raw('stock_movements.qty * ingredients.cost_price'));
    $monthlySalary = Salary::whereYear('created_at', $year)
      ->whereMonth('created_at', $month)
      ->sum('amount');

    $monthlyExpense = $monthlyExpense + $monthlySalary;

    $monthlyProfit = $monthlyIncome - $monthlyExpense;

    /* =======================
       YEARLY
    ======================= */

    $yearlyIncome = Order::where('status', 'closed')
        ->whereYear('created_at', $year)
        ->sum('total')
        +
        Booking::where('status', 'checked_out')
        ->whereYear('updated_at', $year)
        ->sum('total');
    $yearlySalary = Salary::whereYear('salary_month', $year)
    ->sum('amount');

    /* =======================
       TODAY
    ======================= */

    $todayRestaurant = Order::where('status', 'closed')
        ->whereDate('created_at', today())
        ->sum('total');

    $todayRooms = Booking::where('status', 'checked_out')
        ->whereDate('updated_at', today())
        ->sum('total');

    /* =======================
       SUPPLIERS
    ======================= */

    $supplierPayable = Supplier::sum('balance');

    /* =======================
       CHART DATA
    ======================= */

    $monthlyData = [];

for ($m = 1; $m <= 12; $m++) {

    $income = Order::where('status', 'closed')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $m)
        ->sum('total')
        +
        Booking::where('status', 'checked_out')
        ->whereYear('updated_at', $year)
        ->whereMonth('updated_at', $m)
        ->sum('total');

    $expense = StockMovement::where('type', 'ISSUE')
        ->whereYear('stock_movements.created_at', $year)
        ->whereMonth('stock_movements.created_at', $m)
        ->join('ingredients', 'stock_movements.ingredient_id', '=', 'ingredients.id')
        ->sum(DB::raw('stock_movements.qty * ingredients.cost_price'));

    $monthlyData[] = [
        'month' => $m,
        'income' => $income,
        'expense' => $expense
    ];
}


    return view('finance.index', compact(
    'selectedMonth',
    'restaurantSales',
    'roomRevenue',
    'totalIncome',
    'ingredientExpense',
    'salaryExpense',
    'totalExpense',
    'netProfit',
    'profitMargin',
    'monthlyRestaurant',
    'monthlyRooms',
    'monthlyIncome',
    'monthlyExpense',
	'monthlySalary',
    'monthlyProfit',
    'yearlyIncome',
    'todayRestaurant',
    'todayRooms',
    'supplierPayable',
    'monthlyData'
));


}

}

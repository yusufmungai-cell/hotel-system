<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Booking;
use App\Models\StockMovement;
use App\Models\Room;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceMonth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentYear = $today->year;

        /*
        ===================================
        ATTENDANCE APPROVAL STATUS
        ===================================
        */

        $attendanceMonth = AttendanceMonth::where('year',$today->year)
            ->where('month',$today->month)
            ->first();

        if(!$attendanceMonth){
            $approvalStatus = 'missing';
        }elseif($attendanceMonth->status == 'approved'){
            $approvalStatus = 'approved';
        }else{
            $approvalStatus = 'pending';
        }

        /*
        ===================================
        LIVE STAFF STATUS
        ===================================
        */

        $employees = Employee::with(['attendances' => function($q) use ($today){
            $q->whereDate('date',$today);
        }])->get();

        $inside = [];
        $left = [];
        $absent = [];

        foreach($employees as $employee){

            $attendance = $employee->attendances->first();

            if(!$attendance){
                $absent[] = $employee;
                continue;
            }

            if($attendance->time_in && !$attendance->time_out){
                $inside[] = $employee;
            }else{
                $left[] = $employee;
            }
        }

        /*
        ===================================
        TODAY SUMMARY
        ===================================
        */

        $todayRestaurant = Order::where('status','closed')
            ->whereDate('created_at',$today)
            ->sum('total');

        $todayRooms = Booking::where('status','checked_out')
            ->whereDate('updated_at',$today)
            ->sum('total');

        $todayIncome = $todayRestaurant + $todayRooms;

        $todayExpense = StockMovement::where('type','ISSUE')
            ->whereDate('stock_movements.created_at',$today)
            ->join('ingredients','stock_movements.ingredient_id','=','ingredients.id')
            ->sum(DB::raw('stock_movements.qty * ingredients.cost_price'));

        $todayProfit = $todayIncome - $todayExpense;

        /*
        ===================================
        HOTEL STATUS
        ===================================
        */

        $totalRooms = Room::count();
        $occupiedRooms = Booking::where('status','checked_in')->count();

        $occupancyRate = $totalRooms > 0
            ? ($occupiedRooms / $totalRooms) * 100
            : 0;

        $employeeCount = Employee::count();

        /*
        ===================================
        YEARLY FINANCIAL GRAPH
        ===================================
        */

        $restaurantData = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('status','closed')
            ->whereYear('created_at',$currentYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total','month');

        $roomData = Booking::select(
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('SUM(total) as total')
            )
            ->where('status','checked_out')
            ->whereYear('updated_at',$currentYear)
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->pluck('total','month');

        $expenseData = StockMovement::select(
                DB::raw('MONTH(stock_movements.created_at) as month'),
                DB::raw('SUM(stock_movements.qty * ingredients.cost_price) as total')
            )
            ->join('ingredients','stock_movements.ingredient_id','=','ingredients.id')
            ->where('type','ISSUE')
            ->whereYear('stock_movements.created_at',$currentYear)
            ->groupBy(DB::raw('MONTH(stock_movements.created_at)'))
            ->pluck('total','month');

        $monthlyData = [];

        for ($m = 1; $m <= 12; $m++) {

            $income =
                ($restaurantData[$m] ?? 0)
                +
                ($roomData[$m] ?? 0);

            $expense = $expenseData[$m] ?? 0;

            $monthlyData[] = [
                'month' => date('M', mktime(0,0,0,$m,1)),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'profit' => (float) ($income - $expense)
            ];
        }

        /*
        ===================================
        TOP SELLING ITEMS
        ===================================
        */

        $topItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.qty) as total_qty'))
            ->groupBy('menu_items.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        /*
        ===================================
        FINAL DASHBOARD VIEW
        ===================================
        */

        return view('dashboard', compact(
    'approvalStatus',
    'inside',
    'left',
    'absent',
    'today',
    'todayRestaurant',
    'todayRooms',
    'todayIncome',
    'todayExpense',
    'todayProfit',
    'totalRooms',
    'occupiedRooms',
    'occupancyRate',
    'employeeCount',
    'monthlyData',
    'topItems',
    'currentYear'
));
    }
}
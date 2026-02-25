<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceApprovalController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SalaryController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\BookingController;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StockLedgerController;
use App\Http\Controllers\StockDashboardController;
use App\Http\Controllers\BarController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PayrollReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class,'index'])->name('dashboard');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:Admin'])->group(function () {

    /*
    | PROFILE
    */
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');

    /*
    | COMPANY SETTINGS (business info)
    */
    Route::get('/company-settings', [CompanySettingController::class,'index'])->name('company.settings');
    Route::post('/company-settings', [CompanySettingController::class,'store']);
    Route::post('/company-settings/update', [CompanySettingController::class,'update'])->name('company.settings.update');

    /*
    | SYSTEM SETTINGS (toggles like kitchen routing)
    */
    Route::get('/settings', [SettingsController::class,'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class,'update'])->name('settings.update');

    /*
    | EMPLOYEES
    */
    Route::get('/employees', [EmployeeController::class,'index']);
    Route::get('/employees/create', [EmployeeController::class,'create']);
    Route::post('/employees', [EmployeeController::class,'store']);

    /*
    | ATTENDANCE
    */
    Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');
    Route::post('/attendance/clockin', [AttendanceController::class,'clockIn'])->name('attendance.clockin');
    Route::post('/attendance/clockout', [AttendanceController::class,'clockOut'])->name('attendance.clockout');

    Route::get('/attendance/approval', [AttendanceApprovalController::class,'index'])->name('attendance.approval');
    Route::post('/attendance/approval/update', [AttendanceApprovalController::class,'update'])->name('attendance.approval.update');
    Route::post('/attendance/approval/approve', [AttendanceApprovalController::class, 'approve'])->name('attendance.approval.approve');

    /*
	|--------------------------------------------------------------------------
	| PAYROLL
	|--------------------------------------------------------------------------
	*/

	Route::get('/payroll', [PayrollController::class,'index'])->name('payroll.index');
	Route::post('/payroll/generate', [PayrollController::class,'generate'])->name('payroll.generate');
	Route::get('/payroll/preview', [PayrollController::class,'preview'])->name('payroll.preview');
	Route::post('/payroll/post', [PayrollController::class,'post'])->name('payroll.post');
	Route::get('/payroll/payslip/{id}', [PayrollController::class,'payslip'])->name('payroll.payslip');
    Route::get('/loans', [LoanController::class,'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class,'store'])->name('loans.store');
	Route::get('/reports/payroll-summary', [PayrollReportController::class,'index'])
    ->name('reports.payroll.summary');
	
    /*
    | FINANCE
    */
    Route::get('/finance', [FinanceController::class,'index']);
    Route::get('/salaries', [SalaryController::class,'index']);
    Route::post('/salaries', [SalaryController::class,'store']);

    /*
    |--------------------------------------------------------------------------
    | POS & ORDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/pos', [OrderController::class,'index'])->name('pos.index');

    Route::get('/orders', [OrderController::class,'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class,'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class,'show'])->name('orders.show');

    Route::post('/orders/{order}/add-item',[OrderController::class,'addItem'])->name('orders.addItem');
    Route::post('/orders/{order}/increase/{item}', [OrderController::class,'increase'])->name('orders.increase');
    Route::post('/orders/{order}/decrease/{item}', [OrderController::class,'decrease'])->name('orders.decrease');
    Route::post('/orders/{order}/remove/{item}', [OrderController::class,'remove'])->name('orders.remove');
    Route::post('/orders/{order}/close',[OrderController::class,'close'])->name('orders.close');
	Route::get('/orders/check-ready',[OrderController::class,'checkReady']);
    
	 /*
    |--------------------------------------------------------------------------
    | PICKUP CONTROLLERS
    |--------------------------------------------------------------------------
    */
	
	Route::get('/pickup',[PickupController::class,'index'])->name('pickup.index');
    Route::post('/pickup/{order}/collect',[PickupController::class,'collect'])->name('pickup.collect');
    Route::get('/pickup/check',[PickupController::class,'check'])->name('pickup.check');
	 /*
    | BAR DISPLAY
    */
	
	Route::get('/bar',[BarController::class,'index'])->name('bar.display');
	Route::post('/bar/{order}/ready',[BarController::class,'ready'])->name('bar.ready');
	
	 /*
    | MENU CONTROLLERS
    */
	Route::get('/menu', [MenuController::class,'index'])->name('menu.index');
	Route::get('/menu/create', [MenuController::class,'create'])->name('menu.create');
	Route::post('/menu', [MenuController::class,'store'])->name('menu.store');
	Route::get('/menu/{menu}/edit', [MenuController::class,'edit'])->name('menu.edit');
	Route::post('/menu/{menu}/update', [MenuController::class,'update'])->name('menu.update');
	
    /*
    | KITCHEN DISPLAY
    */
    Route::get('/kitchen', [KitchenController::class,'index'])->name('kitchen.index');
    Route::post('/kitchen/{id}/ready',[KitchenController::class,'ready'])->name('kitchen.ready');
    Route::get('/kitchen/check',[KitchenController::class,'check'])->name('kitchen.check');
    Route::get('/kitchen/ready-check',[KitchenController::class,'checkReady'])->name('kitchen.checkReady');

 /*
    | BILLING
    */
	
	Route::get('/billing', [BillingController::class,'index'])->name('billing.index');
	Route::post('/billing/{order}/pay', [BillingController::class,'pay'])->name('billing.pay');
    Route::get('/billing/{order}/mpesa', [BillingController::class,'mpesaForm'])->name('billing.mpesa.form');
	Route::post('/billing/{order}/mpesa', [BillingController::class,'mpesaConfirm'])->name('billing.mpesa.confirm');
	Route::get('/receipt/{order}', [BillingController::class,'receipt'])->name('billing.receipt');
	//REPORTS
	Route::get('/reports/z-report', [App\Http\Controllers\ReportController::class,'zReport'])->name('reports.z');
	
    /*
    |--------------------------------------------------------------------------
    | HOTEL ROOMS
    |--------------------------------------------------------------------------
    */

    Route::get('/room-types', [RoomTypeController::class,'index']);
    Route::post('/room-types', [RoomTypeController::class,'store']);
    Route::get('/rooms', [RoomController::class,'index']);
    Route::post('/rooms', [RoomController::class,'store']);
    Route::get('/bookings', [BookingController::class,'index']);
    Route::post('/bookings/checkin', [BookingController::class,'checkin']);
    Route::get('/bookings/checkout/{id}', [BookingController::class,'checkout']);

    /*
    |--------------------------------------------------------------------------
    | INVENTORY
    |--------------------------------------------------------------------------
    */

    Route::get('/suppliers', [SupplierController::class,'index']);
    Route::post('/suppliers', [SupplierController::class,'store']);
    Route::get('/receivings', [ReceivingController::class,'index']);
    Route::post('/receivings', [ReceivingController::class,'store']);
    Route::get('/ingredients', [IngredientController::class,'index']);
    Route::post('/ingredients', [IngredientController::class,'store']);
    Route::get('/production', [ProductionController::class,'index']);
    Route::post('/production', [ProductionController::class,'store']);
    Route::post('/production/submit', [ProductionController::class,'submit']);
    Route::get('/storekeeper/requests', [ProductionController::class,'storekeeperIndex']);
    Route::get('/storekeeper/request/{id}', [ProductionController::class,'show']);
    Route::post('/storekeeper/approve/{id}', [ProductionController::class,'approve']);
    Route::post('/storekeeper/reject/{id}', [ProductionController::class,'reject']);
    Route::get('/stock/ledger', [StockLedgerController::class,'index']);
    Route::get('/stock-dashboard', [StockDashboardController::class,'index']);
});
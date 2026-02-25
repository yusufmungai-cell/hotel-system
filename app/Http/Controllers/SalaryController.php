<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Employee;

class SalaryController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $salaries = Salary::with('employee')->latest()->get();

        return view('salaries.index', compact('employees','salaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'salary_month' => 'required|date'
        ]);

        Salary::create($request->all());

        return back()->with('success','Salary recorded successfully');
    }
}

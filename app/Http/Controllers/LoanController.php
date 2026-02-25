<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Employee;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('employee')->latest()->get();
        $employees = Employee::all();

        return view('loans.index', compact('loans','employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'monthly_deduction' => 'required|numeric|min:1',
        ]);

        Loan::create([
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'balance' => $request->amount,
            'monthly_deduction' => $request->monthly_deduction
        ]);

        return back()->with('success','Loan issued successfully');
    }
}
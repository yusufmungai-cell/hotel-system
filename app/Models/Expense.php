<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expense;

class Expense extends Model
{
    //
}
public function index()
{
    $expenses = Expense::latest()->get();
    return view('expenses.index', compact('expenses'));
}

public function store(Request $request)
{
    Expense::create($request->all());
    return back()->with('success','Expense recorded');
}
protected $fillable = ['category','description','amount'];

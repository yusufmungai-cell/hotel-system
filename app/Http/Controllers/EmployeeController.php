<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\EmploymentType;

class EmployeeController extends Controller
{
    // ===============================
    // LIST
    // ===============================
    public function index()
    {
        $employees = Employee::with(['department','position','employmentType'])->get();
        return view('employees.index', compact('employees'));
    }

    // ===============================
    // CREATE FORM
    // ===============================
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $employmentTypes = EmploymentType::orderBy('name')->get();

        return view('employees.create', compact(
            'departments',
            'positions',
            'employmentTypes'
        ));
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $request->validate([
            'staff_no' => 'required|unique:employees',
            'name' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
        ]);

        Employee::create($request->all());

        return redirect('/employees')->with('success', 'Employee added successfully');
    }

    // ===============================
    // EDIT FORM
    // ===============================
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $employmentTypes = EmploymentType::orderBy('name')->get();

        return view('employees.edit', compact(
            'employee',
            'departments',
            'positions',
            'employmentTypes'
        ));
    }

    // ===============================
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'staff_no' => 'required|unique:employees,staff_no,' . $employee->id,
            'name' => 'required',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
        ]);

        $employee->update($request->all());

        return redirect('/employees')->with('success', 'Employee updated successfully');
    }
}

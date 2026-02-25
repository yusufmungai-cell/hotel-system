<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmploymentType;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $permanent = EmploymentType::where('name','Permanent')->first();

        Employee::updateOrCreate(
            ['staff_no' => 'EMP001'],
            [
                'name' => 'John Manager',
                'employment_type_id' => $permanent->id,
                'monthly_salary' => 60000,
                'min_hours_per_day' => 8
            ]
        );

        Employee::updateOrCreate(
            ['staff_no' => 'EMP002'],
            [
                'name' => 'Mary Reception',
                'employment_type_id' => $permanent->id,
                'monthly_salary' => 35000,
                'min_hours_per_day' => 8
            ]
        );

        Employee::updateOrCreate(
            ['staff_no' => 'EMP003'],
            [
                'name' => 'Peter Chef',
                'employment_type_id' => $permanent->id,
                'monthly_salary' => 45000,
                'min_hours_per_day' => 8
            ]
        );

        Employee::updateOrCreate(
            ['staff_no' => 'EMP004'],
            [
                'name' => 'Grace Cashier',
                'employment_type_id' => $permanent->id,
                'monthly_salary' => 30000,
                'min_hours_per_day' => 8
            ]
        );
    }
}
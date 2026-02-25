<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmploymentType;

class EmploymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        EmploymentType::updateOrCreate(
            ['name' => 'Permanent'],
            ['name' => 'Permanent']
        );

        EmploymentType::updateOrCreate(
            ['name' => 'Casual'],
            ['name' => 'Casual']
        );
    }
}
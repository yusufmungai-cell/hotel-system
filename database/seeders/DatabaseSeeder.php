<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,          // must run first
            AdminSeeder::class,         // admin depends on role
            SystemSeeder::class,

            AccountSeeder::class,
            EmploymentTypeSeeder::class,
            RoomTypeSeeder::class,
			EmployeeSeeder::class,
            // DEMO HOTEL DATA
            DemoHotelSeeder::class,
        ]);
    }
}
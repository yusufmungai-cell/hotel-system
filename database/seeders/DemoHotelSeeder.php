<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Employee;
use App\Models\EmploymentType;
use App\Models\MenuCategory;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoHotelSeeder extends Seeder
{
    public function run(): void
    {
        // ================= ROOMS =================
        $standard = RoomType::firstOrCreate(['name'=>'Standard','price'=>3500]);
        $deluxe   = RoomType::firstOrCreate(['name'=>'Deluxe','price'=>5500]);

        for($i=101;$i<=110;$i++){
            Room::firstOrCreate(['room_number'=>$i,'room_type_id'=>$standard->id]);
        }

        for($i=201;$i<=205;$i++){
            Room::firstOrCreate(['room_number'=>$i,'room_type_id'=>$deluxe->id]);
        }

        // ================= EMPLOYEES =================
        $monthly = EmploymentType::firstOrCreate(['name'=>'monthly']);
        $casual  = EmploymentType::firstOrCreate(['name'=>'casual']);

        Employee::updateOrCreate([
            'staff_no'=>'EMP001',
            'name'=>'John Manager',
            'employment_type_id'=>$monthly->id,
            'monthly_salary'=>45000,
            'min_hours_per_day'=>8
        ]);

        Employee::updateOrCreate([
            'staff_no'=>'EMP002',
            'name'=>'Mary Receptionist',
            'employment_type_id'=>$monthly->id,
            'monthly_salary'=>28000,
            'min_hours_per_day'=>8
        ]);

        Employee::updateOrCreate([
            'staff_no'=>'EMP003',
            'name'=>'Peter Waiter',
            'employment_type_id'=>$casual->id,
            'daily_rate'=>1200,
            'overtime_rate'=>150,
            'min_hours_per_day'=>10
        ]);

        // ================= MENU =================
        $foods = MenuCategory::firstOrCreate(['name'=>'Foods']);
        $drinks = MenuCategory::firstOrCreate(['name'=>'Drinks']);

        Menu::firstOrCreate(['name'=>'Pilau','price'=>450,'menu_category_id'=>$foods->id]);
        Menu::firstOrCreate(['name'=>'Ugali Beef','price'=>600,'menu_category_id'=>$foods->id]);
        Menu::firstOrCreate(['name'=>'Chicken Fry','price'=>750,'menu_category_id'=>$foods->id]);

        Menu::firstOrCreate(['name'=>'Soda','price'=>100,'menu_category_id'=>$drinks->id]);
        Menu::firstOrCreate(['name'=>'Water','price'=>50,'menu_category_id'=>$drinks->id]);
        Menu::firstOrCreate(['name'=>'Tea','price'=>80,'menu_category_id'=>$drinks->id]);
    }
}
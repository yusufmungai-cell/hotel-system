<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run()
    {
        CompanySetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Revelation Garden',
                'address' => 'Garisa Road, Mwingi',
                'phone' => '+254713498493',
                'email' => 'reservations@revelationgardens.co.ke',
                'currency' => 'KES'
            ]
        );
    }
}

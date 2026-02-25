<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        RoomType::insert([
            ['name' => 'Standard', 'price' => 3000],
            ['name' => 'Deluxe', 'price' => 6000],
            ['name' => 'Executive', 'price' => 10000],
        ]);
    }
}

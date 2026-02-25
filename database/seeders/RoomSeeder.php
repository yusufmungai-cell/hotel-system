<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $types = RoomType::pluck('id')->toArray();

        for ($i = 1; $i <= 10; $i++) {
            Room::create([
                'room_number' => '10' . $i,
                'room_type_id' => $types[array_rand($types)],
                'status' => 'available'
            ]);
        }
    }
}

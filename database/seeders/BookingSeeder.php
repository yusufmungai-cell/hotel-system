<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::pluck('id')->toArray();

        if (empty($rooms)) {
            $this->command->warn('⚠ No rooms found. Please seed rooms first.');
            return;
        }

        for ($i = 1; $i <= 15; $i++) {

            $guest = Guest::create([
                'name' => 'Guest ' . $i,
                'phone' => '07000000' . $i,
            ]);

            $checkIn = Carbon::now()->subDays(rand(5, 120));
            $nights = rand(1, 5);
            $checkOut = (clone $checkIn)->addDays($nights);

            $dailyRate = rand(2500, 6000);
            $total = $dailyRate * $nights;

            Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $rooms[array_rand($rooms)],
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'daily_rate' => $dailyRate,
                'total' => $total,
                'status' => 'checked_out',
                'created_at' => $checkIn,
                'updated_at' => $checkOut,
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('guest','room')->get();
        $rooms = Room::where('status','available')->get();

        return view('bookings.index', compact('bookings','rooms'));
    }

    public function checkin(Request $request)
    {
        $guest = Guest::create([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        $room = Room::find($request->room_id);

        $booking = Booking::create([
            'guest_id' => $guest->id,
            'room_id' => $room->id,
            'check_in' => now(),
            'daily_rate' => $room->type->price
        ]);

        $room->status = 'occupied';
        $room->save();

        return back()->with('success','Guest checked in');
    }

    public function checkout($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->check_out = now();

        $days = now()->diffInDays($booking->check_in) ?: 1;

        $booking->total = $days * $booking->daily_rate;

        $booking->status = 'checked_out';
        $booking->save();

        $room = $booking->room;
        $room->status = 'available';
        $room->save();

        return back()->with('success','Guest checked out');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('type')->get();
        $types = RoomType::all();

        return view('rooms.index', compact('rooms','types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required',
            'room_type_id' => 'required'
        ]);

        Room::create($request->all());

        return back()->with('success','Room added');
    }
}

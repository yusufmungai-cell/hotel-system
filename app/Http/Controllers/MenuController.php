<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $items = MenuItem::latest()->get();
        return view('menu.index', compact('items'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'station' => 'required'
        ]);

        MenuItem::create([
            'name' => $request->name,
            'price' => $request->price,
            'station' => $request->station,
            'is_active' => 1
        ]);

        return redirect()->route('menu.index')->with('success','Item added');
    }

    public function edit(MenuItem $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, MenuItem $menu)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'station' => 'required'
        ]);

        $menu->update($request->only('name','price','station'));

        return redirect()->route('menu.index')->with('success','Item updated');
    }
}
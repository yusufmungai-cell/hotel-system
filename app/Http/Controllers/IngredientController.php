<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();

        return view('ingredients.index', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'unit' => 'required'
        ]);

        Ingredient::create([
            'name' => $request->name,
            'unit' => $request->unit
        ]);

        return back()->with('success','Ingredient added');
    }
}

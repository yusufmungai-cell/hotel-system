<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\ReceivingItem;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\StockMovement;

class ReceivingController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        $receivings = Receiving::with('supplier','items')->get();

        return view('receivings.index', compact('suppliers','receivings'));
    }

    public function store(Request $request)
{
    $request->validate([
        'supplier_id' => 'required',
        'ingredient_id' => 'required',
        'qty' => 'required|numeric',
        'price' => 'required|numeric'
    ]);

    $total = $request->qty * $request->price;

    // Create Receiving
    $receiving = Receiving::create([
        'supplier_id' => $request->supplier_id,
        'reference' => $request->reference,
        'total' => $total
    ]);

    // Create Receiving Item
    ReceivingItem::create([
    'receiving_id' => $receiving->id,
    'ingredient_id' => $request->ingredient_id,
    'qty' => $request->qty,
    'price' => $request->price,
    'total' => $request->qty * $request->price,
]);


    
    // 🔥 UPDATE INGREDIENT STOCK
    $ingredient = Ingredient::findOrFail($request->ingredient_id);

    $before = $ingredient->stock;

    $ingredient->stock += $request->qty;
	$ingredient->cost_price = $request->price;
    $ingredient->save();

    $after = $ingredient->stock;

    // 🔥 LOG STOCK MOVEMENT
    StockMovement::create([
        'ingredient_id' => $ingredient->id,
        'type' => 'RECEIVE',
        'qty' => $request->qty,
        'before_qty' => $before,
        'after_qty' => $after,
        'reference' => 'GRN-' . $receiving->id,
        'user_id' => auth()->id()
    ]);

    // Increase supplier balance
    $supplier = Supplier::find($request->supplier_id);
    $supplier->balance += $total;
    $supplier->save();

    return back()->with('success','Goods received & stock updated');
}

}

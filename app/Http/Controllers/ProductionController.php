<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProductionRequest;
use App\Models\ProductionRequestItem;
use App\Models\Ingredient;
use App\Models\StockMovement;

class ProductionController extends Controller
{
    public function index()
    {
        $requests = ProductionRequest::with('items')->get();
        $ingredients = Ingredient::all();

        return view('production.index', compact('requests','ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingredient_id' => 'required',
            'qty' => 'required'
        ]);

        // Find existing pending request OR create new one
        $req = ProductionRequest::firstOrCreate([
            'status' => 'pending'
        ]);

        ProductionRequestItem::create([
            'production_request_id' => $req->id,
            'ingredient_id' => $request->ingredient_id,
            'qty' => $request->qty
        ]);

        return back()->with('success', 'Item added to current request');
    }

    public function submit()
    {
        $req = ProductionRequest::where('status', 'pending')->first();

        if ($req) {
            $req->status = 'submitted';
            $req->save();
        }

        return back()->with('success', 'Request submitted to store');
    }

    public function storekeeperIndex()
    {
        $requests = ProductionRequest::where('status', 'submitted')
                    ->with('items.ingredient')
                    ->get();

        return view('storekeeper.index', compact('requests'));
    }

    public function show($id)
    {
        $request = ProductionRequest::with('items.ingredient')->findOrFail($id);

        return view('storekeeper.show', compact('request'));
    }

    public function approve($id)
    {
        $request = ProductionRequest::with('items')->findOrFail($id);

        foreach ($request->items as $item) {

            $ingredient = Ingredient::find($item->ingredient_id);

            $before = $ingredient->stock;

            // Deduct stock
            $ingredient->stock = $ingredient->stock - $item->qty;
            $ingredient->save();

            $after = $ingredient->stock;

            // Log movement
            StockMovement::create([
                'ingredient_id' => $ingredient->id,
                'type' => 'ISSUE',
                'qty' => $item->qty,
                'before_qty' => $before,
                'after_qty' => $after,
                'reference' => 'PR-' . $request->id,
                'user_id' => auth()->id()
            ]);
        }

        $request->status = 'approved';
        $request->save();

        return redirect('/storekeeper/requests')->with('success', 'Request approved and stock updated');
    }

    public function reject($id)
    {
        $request = ProductionRequest::findOrFail($id);

        $request->status = 'rejected';
        $request->save();

        return redirect('/storekeeper/requests')->with('error', 'Request rejected');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;

class StockLedgerController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('ingredient','user')
                    ->orderBy('created_at','desc')
                    ->get();

        return view('stock.ledger', compact('movements'));
    }
}

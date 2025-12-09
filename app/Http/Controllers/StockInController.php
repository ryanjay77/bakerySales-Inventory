<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // Get recent stock ins for history table
        $stockIns = StockIn::with('product')->latest()->take(10)->get();
        return view('stock-in.index', compact('products', 'stockIns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create Stock In Record
            StockIn::create([
                'product_id' => $request->product_id,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'expiration_date' => $request->expiration_date,
            ]);

            // 2. Update Main Product Table (for POS speed)
            // We update the current selling price and add to the total stock
            $product = Product::find($request->product_id);
            $product->stock += $request->quantity;
            $product->price = $request->price; // Update latest price
            $product->save();
        });

        return back()->with('success', 'Stock Added Successfully!');
    }
}
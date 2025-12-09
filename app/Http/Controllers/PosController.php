<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
   public function index()
    {
        // CHANGED: Use all() instead of where('stock', '>', 0)
        // This ensures products with 0 stock are still visible in the grid
        $products = Product::all(); 
        $categories = $products->pluck('category')->unique();
        return view('pos.index', compact('products', 'categories'));
    }

    // THE TRANSACTIONAL LOGIC
    public function store(Request $request)
    {
        $cart = $request->input('cart'); // Array of {id, quantity}
        
        if(empty($cart)) return response()->json(['error' => 'Cart is empty'], 400);

        try {
            // DB::transaction ensures either EVERYTHING succeeds or NOTHING changes
            // This prevents "half-finished" sales if an error occurs midway
            $transaction = DB::transaction(function () use ($cart) {
                
                $totalAmount = 0;
                
                // 1. Create the Transaction Record
                $tx = Transaction::create([
                    'user_id' => auth()->id(),
                    'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                    'total_amount' => 0, // We calculate this below
                ]);

                foreach ($cart as $item) {
                    // Lock row to prevent race conditions (two people buying the last item at the exact same ms)
                    $product = Product::lockForUpdate()->find($item['id']); 
                    
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Not enough stock for {$product->name}");
                    }

                    // 2. Deduct Stock
                    $product->decrement('stock', $item['quantity']);

                    // 3. Create Transaction Item
                    TransactionItem::create([
                        'transaction_id' => $tx->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price_at_time' => $product->price
                    ]);

                    $totalAmount += ($product->price * $item['quantity']);
                }

                // 4. Update Total
                $tx->update(['total_amount' => $totalAmount]);

                return $tx;
            });

            return response()->json(['success' => true, 'transaction' => $transaction]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
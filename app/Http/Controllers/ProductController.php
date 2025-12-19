<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // 1. Standard Product Entry List
    public function index()
    {
        // Just show all products (Master List)
        $products = Product::with('user')->latest()->get();
        return view('products.entry', compact('products'));
    }

    // 2. NEW: Dedicated Low Stock Page logic
    public function lowStock()
    {
        // Fetch only items with stock < 10, ordered by lowest stock first
        $lowStockProducts = Product::where('stock', '<', 10)
            ->with('user')
            ->orderBy('stock', 'asc')
            ->get();

        return view('products.low-stock', compact('lowStockProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category' => 'required|string',
            'shelf_life' => 'nullable|integer|min:1',
        ], [
            'name.unique' => 'A product with this name already exists.',
        ]);

        Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'shelf_life' => $request->shelf_life ?? 3,
            'user_id' => auth()->id(),
            'price' => 0, 
            'stock' => 0, 
        ]);

        return back()->with('success', 'Product Defined Successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category' => 'required|string',
            'shelf_life' => 'nullable|integer|min:1',
        ]);

        // Update shelf life if provided, otherwise keep existing
        $data = $validated;
        if($request->has('shelf_life')) {
            $data['shelf_life'] = $request->shelf_life;
        }

        $product->update($data);

        return back()->with('success', 'Product Details Updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product Deleted.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Needed for update logic

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('user')->latest()->get();
        return view('products.entry', compact('products'));
    }

    public function store(Request $request)
    {
        // CHANGED: Added 'unique:products,name' to prevent duplicates
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'category' => 'required|string',
        ], [
            'name.unique' => 'A product with this name already exists.', // Custom Error Message
        ]);

        Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'user_id' => auth()->id(),
            'price' => 0, 
            'stock' => 0, 
        ]);

        return back()->with('success', 'Product Defined Successfully!');
    }

    public function update(Request $request, Product $product)
    {
        // CHANGED: Allow same name if it belongs to the current product (ignore self)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category' => 'required|string',
        ]);

        $product->update($validated);

        return back()->with('success', 'Product Details Updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product Deleted.');
    }
}
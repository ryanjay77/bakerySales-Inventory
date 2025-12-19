<x-app-layout>
    <!-- PRINT STYLES -->
    <style>
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; padding: 20px; background: white; }
            .no-print { display: none !important; }
            .print-header { display: block !important; }
            input, select { border: none !important; background: transparent !important; padding: 0 !important; }
            .action-col { display: none !important; }
        }
    </style>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Product Entry & Inventory</h2>
                <p class="text-sm text-gray-500">Define products and monitor stock levels.</p>
                
                @if ($errors->any())
                    <div class="mt-2 p-2 bg-red-50 text-red-600 rounded border border-red-200 text-sm font-bold">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            
            <div class="flex gap-2 no-print">
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                    <span>🖨️</span> Print Inventory
                </button>
            </div>
        </div>

        <!-- ADD PRODUCT FORM -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 no-print">
            <h3 class="text-sm font-bold text-gray-700 uppercase mb-2">Add New Product</h3>
            <form action="{{ route('products.store') }}" method="POST" class="flex flex-wrap gap-2 items-end">
                @csrf
                
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 font-bold">Product Name</label>
                    <input type="text" name="name" placeholder="e.g. Pandesal" value="{{ old('name') }}" required 
                           class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-48 {{ $errors->has('name') ? 'border-red-500 bg-red-50' : '' }}">
                </div>
                
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 font-bold">Category</label>
                    <select name="category" class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-40">
                        <option value="Bread">Bread</option>
                        <option value="Pastry">Pastry</option>
                        <option value="Cake">Cake</option>
                        <option value="Ice Cream">Ice Cream</option>
                        <option value="Beverage">Beverage</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 font-bold">Shelf Life (Days)</label>
                    <input type="number" name="shelf_life" placeholder="3" min="1" value="3" 
                           class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-24 text-center" 
                           title="Days before product is considered waste">
                </div>
                
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md transition-colors h-[38px]">
                    + Add Product
                </button>
            </form>
        </div>

        <!-- MAIN TABLE -->
        <div id="print-area" class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            
            <div class="print-header hidden p-4 text-center border-b border-gray-200 mb-4">
                <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wide">Pan de Pane Bakeshoppe</h1>
                <p class="text-sm text-gray-500">Current Inventory Status Report</p>
                <p class="text-xs text-gray-400 mt-1">Generated: {{ now()->format('F d, Y h:i A') }}</p>
            </div>

            <div class="p-4 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                <div class="text-sm text-amber-800">
                    <strong>Total Items:</strong> {{ $products->count() }} Products Defined
                </div>
                <div class="text-sm text-amber-800">
                    <strong>Total Inventory Value:</strong> 
                    <span class="font-mono font-bold text-lg">
                        ₱{{ number_format($products->sum(function($p){ return $p->price * $p->stock; }), 2) }}
                    </span>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm font-semibold border-b border-gray-200">
                        <th class="p-4 w-16">ID</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Shelf Life</th>
                        <th class="p-4 text-center">Current Stock</th>
                        <th class="p-4 text-right">Unit Price</th>
                        <th class="p-4 text-right action-col">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-amber-50 transition-colors group">
                        <td class="p-4 font-mono text-gray-500">#{{ $product->id }}</td>
                        <td class="p-4">
                            <input type="text" name="name" form="update-form-{{ $product->id }}" value="{{ $product->name }}" class="bg-transparent border-none p-0 focus:ring-0 font-medium text-gray-800 w-full">
                        </td>
                        <td class="p-4">
                            <select name="category" form="update-form-{{ $product->id }}" class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-gray-500 uppercase w-full cursor-pointer">
                                <option value="Bread" {{ $product->category == 'Bread' ? 'selected' : '' }}>Bread</option>
                                <option value="Pastry" {{ $product->category == 'Pastry' ? 'selected' : '' }}>Pastry</option>
                                <option value="Cake" {{ $product->category == 'Cake' ? 'selected' : '' }}>Cake</option>
                                <option value="Ice Cream" {{ $product->category == 'Ice Cream' ? 'selected' : '' }}>Ice Cream</option>
                                <option value="Beverage" {{ $product->category == 'Beverage' ? 'selected' : '' }}>Beverage</option>
                                <option value="Concessionaire" {{ $product->category == 'Concessionaire' ? 'selected' : '' }}>Concessionaire</option>
                            </select>
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $product->shelf_life ?? '3' }} Days</td>
                        <td class="p-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->stock }} Units
                            </span>
                        </td>
                        <td class="p-4 text-right text-sm text-gray-600 font-mono">₱{{ number_format($product->price, 2) }}</td>
                        <td class="p-4 text-right flex justify-end gap-3 opacity-50 group-hover:opacity-100 transition-opacity action-col">
                            <form id="update-form-{{ $product->id }}" action="{{ route('products.update', $product) }}" method="POST" class="hidden">
                                @csrf @method('PUT')
                            </form>
                            <button type="submit" form="update-form-{{ $product->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Save</button>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product definition?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($products->isEmpty())
                <div class="p-8 text-center text-gray-400">No products defined yet.</div>
            @endif
        </div>
    </div>
</x-app-layout>
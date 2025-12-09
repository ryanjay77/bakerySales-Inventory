<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Product Entry</h2>
                <p class="text-sm text-gray-500">Define master products here. Add stock in "Stock In" page.</p>
            </div>
            
            <!-- Add Product Form (Simplified) -->
            <form action="{{ route('products.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Product Name" required class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500 w-64">
                <select name="category" class="rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    <option value="Bread">Bread</option>
                    <option value="Pastry">Pastry</option>
                    <option value="Cake">Cake</option>
                    <option value="Beverage">Beverage</option>
                </select>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">
                    + Create Product
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm font-semibold border-b border-gray-200">
                        <th class="p-4">Product ID</th>
                        <th class="p-4">Product Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Added By</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-amber-50 transition-colors group">
                        
                        <!-- ID -->
                        <td class="p-4 font-mono text-gray-500">
                            #{{ $product->id }}
                        </td>

                        <!-- NAME -->
                        <td class="p-4">
                            <input type="text" 
                                   name="name" 
                                   form="update-form-{{ $product->id }}"
                                   value="{{ $product->name }}" 
                                   class="bg-transparent border-none p-0 focus:ring-0 font-medium text-gray-800 w-full placeholder-gray-400">
                        </td>

                        <!-- CATEGORY -->
                        <td class="p-4">
                            <select name="category" 
                                    form="update-form-{{ $product->id }}" 
                                    class="bg-transparent border-none p-0 focus:ring-0 text-xs font-bold text-gray-500 uppercase w-full cursor-pointer">
                                <option value="Bread" {{ $product->category == 'Bread' ? 'selected' : '' }}>Bread</option>
                                <option value="Pastry" {{ $product->category == 'Pastry' ? 'selected' : '' }}>Pastry</option>
                                <option value="Cake" {{ $product->category == 'Cake' ? 'selected' : '' }}>Cake</option>
                                <option value="Beverage" {{ $product->category == 'Beverage' ? 'selected' : '' }}>Beverage</option>
                            </select>
                        </td>

                        <!-- ADDED BY -->
                        <td class="p-4 text-sm text-gray-600">
                            {{ $product->user->name ?? 'System' }}
                        </td>

                        <!-- ACTIONS -->
                        <td class="p-4 text-right flex justify-end gap-3 opacity-50 group-hover:opacity-100 transition-opacity">
                            <form id="update-form-{{ $product->id }}" action="{{ route('products.update', $product) }}" method="POST" class="hidden">
                                @csrf
                                @method('PUT')
                            </form>
                            <button type="submit" form="update-form-{{ $product->id }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">Save</button>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product definition?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
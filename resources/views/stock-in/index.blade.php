<x-app-layout>
    <div class="flex flex-col md:flex-row gap-6">
        
        <!-- Left: Stock In Form -->
        <div class="w-full md:w-1/3 space-y-6">
            <h2 class="text-2xl font-bold text-gray-800">📥 Stock In</h2>
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('stock-in.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Product Selection -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Select Product</label>
                        <select id="product-select" name="product_id" required class="w-full rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500" onchange="updateProductId()">
                            <option value="">-- Choose Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Auto-Displayed Product ID -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Product ID</label>
                        <input type="text" id="display-id" disabled class="w-full bg-gray-100 text-gray-500 rounded-lg border-gray-300 font-mono">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Price per Unit</label>
                        <div class="relative">
                            <!-- CHANGED: $ to ₱ -->
                            <span class="absolute left-3 top-2 text-gray-500">₱</span>
                            <input type="number" step="0.01" name="price" required class="w-full pl-7 rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Quantity</label>
                        <input type="number" name="quantity" required class="w-full rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <!-- Expiration Date -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Expiration Date</label>
                        <input type="date" name="expiration_date" required class="w-full rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold shadow-md transition-transform active:scale-95">
                        Confirm Stock In
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Recent History -->
        <div class="w-full md:w-2/3 space-y-6">
            <h2 class="text-xl font-bold text-gray-800">Recent Stock History</h2>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase border-b border-gray-100">
                        <tr>
                            <th class="p-4">Date Added</th>
                            <th class="p-4">Product ID</th>
                            <th class="p-4">Product Name</th>
                            <th class="p-4">Price</th>
                            <th class="p-4">Qty</th>
                            <th class="p-4">Expires</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($stockIns as $stock)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 text-gray-500">{{ $stock->created_at->format('M d, Y') }}</td>
                            <td class="p-4 font-mono text-gray-500">#{{ $stock->product_id }}</td>
                            <td class="p-4 font-bold text-gray-800">{{ $stock->product->name }}</td>
                            <!-- CHANGED: $ to ₱ -->
                            <td class="p-4 text-green-600 font-medium">₱{{ number_format($stock->price, 2) }}</td>
                            <td class="p-4">{{ $stock->quantity }}</td>
                            <td class="p-4 text-red-500">{{ \Carbon\Carbon::parse($stock->expiration_date)->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function updateProductId() {
            const select = document.getElementById('product-select');
            const display = document.getElementById('display-id');
            display.value = select.value ? '#' + select.value : '';
        }
    </script>
</x-app-layout>
<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-red-700 flex items-center gap-2">
                    <span>⚠️</span> Low Stock Alerts
                </h2>
                <p class="text-sm text-gray-500">Items that are below 10 units and need immediate replenishment.</p>
            </div>
            
            <a href="{{ route('stock-in.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2">
                <span>📥</span> Go to Stock In
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-red-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-red-50 text-red-800 text-sm font-bold border-b border-red-100 uppercase">
                        <th class="p-4">Product Name</th>
                        <th class="p-4">Category</th>
                        <th class="p-4 text-center">Current Stock</th>
                        <th class="p-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lowStockProducts as $product)
                    <tr class="hover:bg-red-50 transition-colors">
                        <td class="p-4 font-bold text-gray-800">
                            {{ $product->name }}
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            {{ $product->category }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-lg font-bold {{ $product->stock == 0 ? 'bg-gray-200 text-gray-600' : 'bg-red-100 text-red-600' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            @if($product->stock == 0)
                                <span class="text-xs font-bold text-white bg-gray-500 px-2 py-1 rounded">OUT OF STOCK</span>
                            @else
                                <span class="text-xs font-bold text-white bg-red-500 px-2 py-1 rounded">CRITICAL</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-4xl">✅</span>
                                <span class="text-lg font-medium">All stocks are healthy!</span>
                                <span class="text-sm">No items are below 10 units.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
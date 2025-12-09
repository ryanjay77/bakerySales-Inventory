<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📤 Stock Out (Sales History)</h2>
                <p class="text-sm text-gray-500">List of all items deducted from inventory.</p>
            </div>
            
            <!-- Export Button (Visual Only) -->
            <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                🖨️ Print Report
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase border-b border-gray-200">
                        <th class="p-4">Transaction ID</th>
                        <th class="p-4">Product ID</th>
                        <th class="p-4">Product Name</th> <!-- Added for clarity -->
                        <th class="p-4">Price (Sold)</th>
                        <th class="p-4">Qty</th>
                        <th class="p-4">Date Added</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($stockOuts as $item)
                    <tr class="hover:bg-gray-50">
                        <!-- Transaction ID -->
                        <td class="p-4 font-mono text-amber-600 font-bold">
                            {{ $item->transaction->transaction_code ?? 'N/A' }}
                        </td>

                        <!-- Product ID -->
                        <td class="p-4 font-mono text-gray-500">
                            #{{ $item->product_id }}
                        </td>

                        <!-- Product Name -->
                        <td class="p-4 font-medium text-gray-800">
                            {{ $item->product->name ?? 'Unknown Product' }}
                        </td>

                        <!-- Price -->
                        <td class="p-4 text-gray-600">
                            <!-- CHANGED: $ to ₱ -->
                            ₱{{ number_format($item->price_at_time, 2) }}
                        </td>

                        <!-- Qty -->
                        <td class="p-4 font-bold text-gray-800">
                            {{ $item->quantity }}
                        </td>

                        <!-- Date Added -->
                        <td class="p-4 text-gray-500">
                            {{ $item->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">
                            No stock out records found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Pagination Links -->
            <div class="p-4 border-t border-gray-100">
                {{ $stockOuts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
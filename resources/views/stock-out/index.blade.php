<x-app-layout>
    <!-- PRINT STYLES -->
    <style>
        @media print {
            /* 1. Hide everything on the page */
            body * {
                visibility: hidden;
            }

            /* 2. Show only the specific print container and its children */
            #print-area, #print-area * {
                visibility: visible;
            }

            /* 3. Position the print area at the absolute top-left */
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                background-color: white;
                border: none;
                box-shadow: none;
            }

            /* 4. Hide specific elements inside the container (like buttons/pagination) */
            .no-print {
                display: none !important;
            }

            /* 5. Show the print-only header */
            .print-header {
                display: block !important;
            }
        }
    </style>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📤 Stock Out (Sales History)</h2>
                <p class="text-sm text-gray-500">List of all items deducted from inventory.</p>
            </div>
            
            <!-- Print Button with 'no-print' class -->
            <button onclick="window.print()" class="no-print bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center gap-2">
                <span>🖨️</span> Print Report
            </button>
        </div>

        <!-- PRINT AREA CONTAINER -->
        <div id="print-area" class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            
            <!-- PRINT-ONLY HEADER (Hidden on screen, Visible on paper) -->
            <div class="print-header hidden p-4 text-center border-b border-gray-200 mb-4">
                <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wide">Pan de Pane Bakeshoppe</h1>
                <p class="text-sm text-gray-500">Official Stock Out / Sales Report</p>
                <p class="text-xs text-gray-400 mt-1">Generated: {{ now()->format('F d, Y h:i A') }}</p>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase border-b border-gray-200">
                        <th class="p-4">Transaction ID</th>
                        <th class="p-4">Product ID</th>
                        <th class="p-4">Product Name</th> 
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
            
            <!-- Pagination Links (Hidden during print) -->
            <div class="p-4 border-t border-gray-100 no-print">
                {{ $stockOuts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
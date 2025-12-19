<x-app-layout>
    <style>
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
            .print-header { display: block !important; }
        }
    </style>

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📤 Sales Report</h2>
                <p class="text-sm text-gray-500">History of sold items.</p>
            </div>
            
            <div class="flex gap-2">
                <!-- Date Filter -->
                <form method="GET" action="{{ route('stock-out.index') }}" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm border-gray-300 rounded-md">
                    <span>to</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm border-gray-300 rounded-md">
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-bold hover:bg-blue-700">Filter</button>
                </form>

                <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-gray-700 flex items-center gap-2">
                    <span>🖨️</span> Print
                </button>
            </div>
        </div>

        <!-- REPORT SUMMARY BOX -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-center">
                <p class="text-xs font-bold text-amber-600 uppercase">Total Items Sold</p>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalItemsSold) }}</p>
            </div>
            <div class="bg-green-50 border border-green-200 p-4 rounded-xl text-center">
                <p class="text-xs font-bold text-green-600 uppercase">Total Sales Amount</p>
                <p class="text-3xl font-bold text-gray-800">₱{{ number_format($totalSalesAmount, 2) }}</p>
            </div>
        </div>

        <div id="print-area" class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
            <div class="print-header hidden p-4 text-center border-b border-gray-200 mb-4">
                <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wide">Pan de Pane Bakeshoppe</h1>
                <p class="text-sm text-gray-500">Sales Report: {{ \Carbon\Carbon::parse($startDate)->format('M d') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase border-b border-gray-200">
                        <th class="p-4">Transaction ID</th>
                        <th class="p-4">Product Name</th>
                        <th class="p-4 text-center">Qty</th>
                        <th class="p-4 text-right">Price</th>
                        <th class="p-4 text-right">Subtotal</th>
                        <th class="p-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($stockOuts as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-mono text-gray-500 text-xs">{{ $item->transaction->transaction_code ?? 'N/A' }}</td>
                        <td class="p-4 font-bold text-gray-800">{{ $item->product->name ?? 'Unknown' }}</td>
                        <td class="p-4 text-center">{{ $item->quantity }}</td>
                        <td class="p-4 text-right text-gray-500">₱{{ number_format($item->price_at_time, 2) }}</td>
                        <td class="p-4 text-right font-bold text-green-600">₱{{ number_format($item->quantity * $item->price_at_time, 2) }}</td>
                        <td class="p-4 text-right text-gray-500 text-xs">{{ $item->created_at->format('M d, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-400">No records found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-4 border-t border-gray-100 no-print">
                {{ $stockOuts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6">
        
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- 1. Total Revenue Card -->
            <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                            <span>💰</span>
                        </div>
                        <p class="text-gray-500 font-bold uppercase text-sm">Total<br>Revenue</p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-3xl font-bold text-gray-800">₱{{ number_format($totalRevenue, 2) }}</h3>
                        <p class="text-xs text-green-600 mt-1 font-medium">+ ₱{{ number_format($todaySales ?? 0, 2) }} today</p>
                    </div>
                </div>
            </div>

            <!-- 2. Total Products Card -->
            <a href="{{ route('products.index') }}" class="block transform transition duration-200 hover:scale-105 hover:shadow-lg">
                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                                <span>📦</span>
                            </div>
                            <p class="text-gray-500 font-bold uppercase text-sm">Total<br>Products</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</h3>
                            <p class="text-xs text-blue-500 mt-1 font-bold underline">View Inventory →</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- 3. Low Stock Alert Card -->
            <a href="{{ route('products.index') }}" class="block transform transition duration-200 hover:scale-105 hover:shadow-lg">
                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-red-500 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                                <span>⚠️</span>
                            </div>
                            <p class="text-gray-500 font-bold uppercase text-sm">Low Stock<br>Alerts</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-3xl font-bold text-gray-800">{{ $lowStockCount }}</h3>
                            <p class="text-xs text-red-500 mt-1 font-bold underline">Manage Stock →</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- NEW: Sales Chart Section -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Sales Overview (Last 7 Days)</h3>
            <div class="h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Transaction ID</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Cashier</th>
                            <th class="px-6 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-amber-600 font-medium">{{ $tx->transaction_code }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $tx->created_at->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">
                                    {{ $tx->cashier->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">₱{{ number_format($tx->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">No transactions found yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script to Render Graph -->
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line', // Can be 'bar', 'line', 'pie', etc.
            data: {
                labels: @json($chartLabels), // Data from Controller
                datasets: [{
                    label: 'Daily Revenue (₱)',
                    data: @json($chartValues), // Data from Controller
                    borderColor: '#d97706', // Amber-600 color
                    backgroundColor: 'rgba(217, 119, 6, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3 // Makes lines curvy
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
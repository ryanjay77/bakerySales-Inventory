<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
            
            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-500 uppercase">From:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-500 uppercase">To:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                </div>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-md text-sm font-bold">Filter</button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- 1. Total Sales Card -->
            <a href="{{ route('stock-out.index') }}" class="block transform transition duration-200 hover:scale-105 hover:shadow-lg">
                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                                <span>💰</span>
                            </div>
                            <p class="text-gray-500 font-bold uppercase text-sm">Total<br>Sales</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-3xl font-bold text-gray-800">₱{{ number_format($totalSales, 2) }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalTransactions }} transactions filtered</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- 2. Total Products Card -->
            <a href="{{ route('products.index') }}" class="block transform transition duration-200 hover:scale-105 hover:shadow-lg">
                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500 cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                                <span>📦</span>
                            </div>
                            <p class="text-gray-500 font-bold uppercase text-sm">Inventory<br>Items</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</h3>
                            <p class="text-xs text-blue-500 mt-1 font-bold underline">View Stock →</p>
                        </div>
                    </div>
                </div>
            </a>

            <!-- 3. Low Stock Alert Card -->
            <!-- UPDATED LINK: Point to new Low Stock Page -->
            <a href="{{ route('products.low-stock') }}" class="block transform transition duration-200 hover:scale-105 hover:shadow-lg">
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
                            <p class="text-xs text-red-500 mt-1 font-bold underline">View List →</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Sales Performance</h3>
            <div class="h-64 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script to Render Graph -->
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Sales (₱)',
                    data: @json($chartValues),
                    borderColor: '#d97706',
                    backgroundColor: 'rgba(217, 119, 6, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: function(value) { return '₱' + value; } }
                    }
                }
            }
        });
    </script>
</x-app-layout>
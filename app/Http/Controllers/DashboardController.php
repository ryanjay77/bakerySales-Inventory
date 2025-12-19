<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. DATE FILTERING LOGIC
        // Default to 'This Month' if no filter is provided
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 2. Statistics based on Filter
        $totalSales = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('total_amount');

        $totalTransactions = Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // Total Products (Static - Current State)
        $totalProducts = Product::count();
        
        // Low Stock (Static - Current State)
        $lowStockCount = Product::where('stock', '<', 10)->count();
        
        $recentTransactions = Transaction::with('cashier')
            ->latest()
            ->take(5)
            ->get();

        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');

        // 3. Chart Data (Respects the Filter)
        $salesData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $chartLabels = $salesData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'));
        $chartValues = $salesData->pluck('total');

        return view('dashboard', compact(
            'totalSales', 
            'totalProducts', 
            'lowStockCount', 
            'recentTransactions',
            'todaySales',
            'chartLabels', 
            'chartValues',
            'startDate',
            'endDate',
            'totalTransactions'
        ));
    }
}
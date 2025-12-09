<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Existing Stats
        $totalRevenue = Transaction::sum('total_amount');
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock', '<', 10)->count();
        
        $recentTransactions = Transaction::with('cashier')
            ->latest()
            ->take(5)
            ->get();

        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');

        // 2. NEW: Chart Data (Last 7 Days Sales)
        $salesData = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7)) // Get last 7 days
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        // Format data for Chart.js
        $chartLabels = $salesData->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('M d'); // e.g. "Dec 01"
        });
        $chartValues = $salesData->pluck('total');

        return view('dashboard', compact(
            'totalRevenue', 
            'totalProducts', 
            'lowStockCount', 
            'recentTransactions',
            'todaySales',
            'chartLabels', // Passing these to the view
            'chartValues'
        ));
    }
}
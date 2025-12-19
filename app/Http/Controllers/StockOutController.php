<?php

namespace App\Http\Controllers;

use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Logic
        $startDate = $request->input('start_date', Carbon::now()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // 2. Query
        $query = TransactionItem::with(['product', 'transaction'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest();

        // 3. Calculate Totals for the Report
        // We clone the query to get sums without breaking pagination
        $totalSalesAmount = (clone $query)->get()->sum(function($item) {
            return $item->quantity * $item->price_at_time;
        });
        
        $totalItemsSold = (clone $query)->sum('quantity');

        // 4. Get Paginated Results
        $stockOuts = $query->paginate(50); // Show 50 per page for reports

        return view('stock-out.index', compact('stockOuts', 'startDate', 'endDate', 'totalSalesAmount', 'totalItemsSold'));
    }
}
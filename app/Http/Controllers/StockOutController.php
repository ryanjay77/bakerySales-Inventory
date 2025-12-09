<?php

namespace App\Http\Controllers;

use App\Models\TransactionItem;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        // Fetch all sold items, joined with the Transaction info
        // We order by latest date added
        $stockOuts = TransactionItem::with(['product', 'transaction'])
            ->latest()
            ->paginate(20); // Show 20 per page

        return view('stock-out.index', compact('stockOuts'));
    }
}
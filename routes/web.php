<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockInController;   // <--- THIS WAS MISSING
use App\Http\Controllers\StockOutController;  // <--- ADD THIS TOO
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Default Breeze Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Bakery System Routes
Route::middleware(['auth'])->group(function () {

    // POS Route (Accessible by Admin & Cashier)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/transaction', [PosController::class, 'store'])->name('pos.store');

    // ADMIN ONLY Routes
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // 1. Product Entry
        Route::resource('products', ProductController::class);
        
        // 2. Stock In
        Route::get('/stock-in', [StockInController::class, 'index'])->name('stock-in.index');
        Route::post('/stock-in', [StockInController::class, 'store'])->name('stock-in.store');

        // 3. Stock Out
        Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');

        // 4. User Management
        Route::resource('users', UserController::class)->only(['index', 'store', 'destroy']);
    });
});

require __DIR__.'/auth.php';
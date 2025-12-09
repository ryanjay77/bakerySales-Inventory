<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;

    // We use guarded = [] to turn off Mass Assignment protection for this model.
    // This allows 'product_id', 'price', 'quantity', and 'expiration_date' to be saved safely
    // via the StockInController.
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
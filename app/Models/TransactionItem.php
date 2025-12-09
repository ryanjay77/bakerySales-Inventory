<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $guarded = [];

    /**
     * The transaction this item belongs to.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * The product associated with this item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
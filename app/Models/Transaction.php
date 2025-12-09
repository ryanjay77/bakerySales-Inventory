<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $guarded = [];

    public function items() {
        return $this->hasMany(TransactionItem::class);
    }
    
   public function cashier()
{
    // Assuming your users table is 'users' and foreign key in transactions is 'user_id'
    return $this->belongsTo(User::class, 'user_id');
}
    
}

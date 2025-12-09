<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    // Setting guarded to empty array allows ALL columns to be saved.
    // This fixes the issue where data (like name or user_id) was being blocked.
    protected $guarded = []; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
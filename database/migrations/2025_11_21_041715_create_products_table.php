<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            
            // These act as the "Cache" or "Current State" for the POS.
            // They are updated whenever you create a "Stock In" record.
            $table->decimal('price', 10, 2)->default(0); 
            $table->integer('stock')->default(0);
            
            // This stores who defined the product (Master Data)
            // If you are starting fresh, you can add it here directly.
            // If you already have the table, the 'stock_ins' migration (previous file) will add this column for you.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
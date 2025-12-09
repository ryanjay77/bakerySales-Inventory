<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create Stock Ins Table
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2); // Cost or Selling Price
            $table->integer('quantity');
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });

        // 2. Add 'user_id' to products if it doesn't exist (for "Added By" feature)
        if (!Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->after('id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('stock_ins');
        if (Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
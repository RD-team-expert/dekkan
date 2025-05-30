<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('barcode')->nullable()->unique();
            $table->string('category')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('quantity_alert');
            $table->integer('min_order');
            $table->integer('stock_quantity')->default(0);
            $table->index(['category', 'stock_quantity']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

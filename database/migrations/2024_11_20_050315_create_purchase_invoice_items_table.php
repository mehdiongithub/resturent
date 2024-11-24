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
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchase_invoice_id');
            $table->uuid('product_id');
            $table->integer('quantity');
            $table->enum('uom', ['liter', 'kg', 'g', 'ml','dozen']);
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('expiry_date');

            $table->timestamps(); // Includes created_at and updated_at
            $table->softDeletes(); // Adds the deleted_at column for soft deletes

            // Foreign Key Constraints
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_items');
    }
};

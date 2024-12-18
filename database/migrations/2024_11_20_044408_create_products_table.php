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
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('store_id');
            $table->string('name', 255);
            $table->enum('uom', ['liter', 'kg','dozen']);
            $table->enum('product_type', ['long_term', 'short_term']);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps(); // Includes created_at and updated_at
            $table->softDeletes(); // Adds the deleted_at column for soft deletes

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            
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

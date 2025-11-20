<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic & searchable fields
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();

            // Inventory / SKU
            $table->string('sku')->nullable()->unique();
            $table->integer('stock')->default(0);

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // Relations
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Dimensions & extra data
            $table->string('images')->nullable();
            $table->json('attributes')->nullable();

            // Optional metrics
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('sold_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['name']);
            $table->index(['price']);
            $table->index(['is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

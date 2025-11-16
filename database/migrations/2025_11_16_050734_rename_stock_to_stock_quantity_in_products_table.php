<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(0)->after('sku');
        });

        // Copy data from stock to stock_quantity
        DB::statement('UPDATE products SET stock_quantity = stock');

        // Drop old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('sku');
            // Copy data back
            DB::statement('UPDATE products SET stock = stock_quantity');
            // Drop new column
            $table->dropColumn('stock_quantity');
        });
    }
};

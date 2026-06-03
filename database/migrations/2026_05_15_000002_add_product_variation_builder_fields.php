<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variations', 'attributes')) {
                $table->json('attributes')->nullable()->after('attribute_value');
            }

            if (!Schema::hasColumn('product_variations', 'compare_at_price')) {
                $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            }

            if (!Schema::hasColumn('product_variations', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->nullable()->after('compare_at_price');
            }

            if (!Schema::hasColumn('product_variations', 'track_stock')) {
                $table->boolean('track_stock')->default(true)->after('stock_quantity');
            }

            if (!Schema::hasColumn('product_variations', 'is_in_stock')) {
                $table->boolean('is_in_stock')->default(true)->after('track_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            foreach (['attributes', 'compare_at_price', 'cost_price', 'track_stock', 'is_in_stock'] as $column) {
                if (Schema::hasColumn('product_variations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (config('database.default') !== 'sqlite') {
            DB::statement('ALTER TABLE products MODIFY sku VARCHAR(100) NULL');
        }
    }

    public function down(): void
    {
        if (config('database.default') !== 'sqlite') {
            DB::statement('UPDATE products SET sku = CONCAT("PRODUCT-", id) WHERE sku IS NULL');
            DB::statement('ALTER TABLE products MODIFY sku VARCHAR(100) NOT NULL');
        }
    }
};

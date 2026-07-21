<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('session_id', 120)->nullable();
            $table->string('token', 80);
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('variation_id')->nullable()->constrained('product_variations')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('status', 20)->default('active');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['token', 'status']);
            $table->index(['status', 'expires_at']);
            $table->index(['session_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_reservations');
    }
};

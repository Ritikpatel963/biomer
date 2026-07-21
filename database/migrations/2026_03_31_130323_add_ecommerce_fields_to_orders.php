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
        Schema::table('orders', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'return_reason')) {
                $table->text('return_reason')->nullable();
            }
            if (!Schema::hasColumn('orders', 'return_status')) {
                $table->enum('return_status', ['none', 'requested', 'approved', 'rejected', 'refunded'])->default('none');
            }
            if (!Schema::hasColumn('orders', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (!Schema::hasColumn('orders', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'cashfree_order_id')) {
                $table->string('cashfree_order_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'cashfree_payment_id')) {
                $table->string('cashfree_payment_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'shiprocket_order_id')) {
                $table->string('shiprocket_order_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_id']);
            $table->dropColumn([
                'coupon_id', 'discount_amount', 'tax_amount', 'net_amount', 
                'return_reason', 'return_status'
            ]);
            
            $columnsToDrop = [];
            foreach (['payment_gateway', 'payment_status', 'razorpay_order_id', 'razorpay_payment_id', 'cashfree_order_id', 'cashfree_payment_id', 'shiprocket_order_id'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

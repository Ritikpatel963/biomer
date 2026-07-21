<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'usage_limit')) {
                $table->unsignedInteger('usage_limit')->nullable()->after('min_order_amount');
            }

            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->unsignedInteger('used_count')->default(0)->after('usage_limit');
            }

            if (!Schema::hasColumn('coupons', 'expires_at')) {
                $table->date('expires_at')->nullable()->after('used_count');
            }

            if (!Schema::hasColumn('coupons', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('expires_at');
            }
        });

        if (Schema::hasColumn('coupons', 'status') && Schema::hasColumn('coupons', 'is_active')) {
            DB::table('coupons')->update([
                'is_active' => DB::raw('status'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (Schema::hasColumn('coupons', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('coupons', 'expires_at')) {
                $table->dropColumn('expires_at');
            }

            if (Schema::hasColumn('coupons', 'used_count')) {
                $table->dropColumn('used_count');
            }

            if (Schema::hasColumn('coupons', 'usage_limit')) {
                $table->dropColumn('usage_limit');
            }
        });
    }
};

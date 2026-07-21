<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('order_returns', 'order_item_id')) {
                $table->foreignId('order_item_id')
                    ->nullable()
                    ->after('order_id')
                    ->constrained('order_items')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('order_returns', 'return_reason')) {
                $table->string('return_reason')->nullable()->after('customer_id');
            }

            if (!Schema::hasColumn('order_returns', 'customer_notes')) {
                $table->text('customer_notes')->nullable()->after('return_reason');
            }

            if (!Schema::hasColumn('order_returns', 'return_tracking_number')) {
                $table->string('return_tracking_number')->nullable()->after('refund_amount');
            }

            if (!Schema::hasColumn('order_returns', 'requested_at')) {
                $table->timestamp('requested_at')->nullable()->after('return_tracking_number');
            }
        });

        if (Schema::hasColumn('order_returns', 'reason') && Schema::hasColumn('order_returns', 'return_reason')) {
            DB::table('order_returns')
                ->whereNull('return_reason')
                ->update(['return_reason' => DB::raw('reason')]);
        }

        if (Schema::hasColumn('order_returns', 'description') && Schema::hasColumn('order_returns', 'customer_notes')) {
            DB::table('order_returns')
                ->whereNull('customer_notes')
                ->update(['customer_notes' => DB::raw('description')]);
        }

        if (Schema::hasColumn('order_returns', 'requested_at')) {
            DB::table('order_returns')
                ->whereNull('requested_at')
                ->update(['requested_at' => DB::raw('created_at')]);
        }
    }

    public function down(): void
    {
        Schema::table('order_returns', function (Blueprint $table) {
            if (Schema::hasColumn('order_returns', 'requested_at')) {
                $table->dropColumn('requested_at');
            }

            if (Schema::hasColumn('order_returns', 'return_tracking_number')) {
                $table->dropColumn('return_tracking_number');
            }

            if (Schema::hasColumn('order_returns', 'customer_notes')) {
                $table->dropColumn('customer_notes');
            }

            if (Schema::hasColumn('order_returns', 'return_reason')) {
                $table->dropColumn('return_reason');
            }

            if (Schema::hasColumn('order_returns', 'order_item_id')) {
                $table->dropForeign(['order_item_id']);
                $table->dropColumn('order_item_id');
            }
        });
    }
};

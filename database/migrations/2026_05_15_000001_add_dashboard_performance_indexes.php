<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'payment_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['payment_status', 'created_at'], 'orders_payment_status_created_at_index');
            });
        }

        if (Schema::hasColumn('orders', 'status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index(['status', 'created_at'], 'orders_status_created_at_index');
            });
        }

        if (Schema::hasColumn('orders', 'payment_gateway')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->index('payment_gateway', 'orders_payment_gateway_index');
            });
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->index('created_at', 'customers_created_at_index');
            $table->index('city', 'customers_city_index');
            $table->index('audience_type', 'customers_audience_type_index');
        });

        if (Schema::hasColumn('products', 'manage_stock') && Schema::hasColumn('products', 'stock_quantity')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['manage_stock', 'stock_quantity'], 'products_manage_stock_quantity_index');
            });
        }

        if (Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('status', 'products_status_index');
            });
        }

        Schema::table('order_returns', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'order_returns_status_created_at_index');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'product_reviews_status_created_at_index');
        });

        Schema::table('blog_reviews', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'blog_reviews_status_created_at_index');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('blog_reviews')) {
            Schema::table('blog_reviews', function (Blueprint $table) {
                $table->dropIndex('blog_reviews_status_created_at_index');
            });
        }

        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $table->dropIndex('product_reviews_status_created_at_index');
            });
        }

        if (Schema::hasTable('order_returns')) {
            Schema::table('order_returns', function (Blueprint $table) {
                $table->dropIndex('order_returns_status_created_at_index');
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'manage_stock') && Schema::hasColumn('products', 'stock_quantity')) {
                    $table->dropIndex('products_manage_stock_quantity_index');
                }
                if (Schema::hasColumn('products', 'status')) {
                    $table->dropIndex('products_status_index');
                }
            });
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_created_at_index');
            $table->dropIndex('customers_city_index');
            $table->dropIndex('customers_audience_type_index');
        });

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'payment_status')) {
                    $table->dropIndex('orders_payment_status_created_at_index');
                }
                if (Schema::hasColumn('orders', 'status')) {
                    $table->dropIndex('orders_status_created_at_index');
                }
                if (Schema::hasColumn('orders', 'payment_gateway')) {
                    $table->dropIndex('orders_payment_gateway_index');
                }
            });
        }
    }
};

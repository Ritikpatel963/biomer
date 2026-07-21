<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->string('logo')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('sku', 100)->unique();
                $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
                $table->string('technical_content')->nullable();
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->decimal('base_price', 10, 2)->default(0);
                $table->decimal('min_price', 10, 2)->nullable();
                $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
                $table->string('featured_image')->nullable();
                $table->string('video_url', 500)->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('image_path', 500);
                $table->unsignedTinyInteger('sort_order')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variations')) {
            Schema::create('product_variations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('sku', 100)->unique();
                $table->string('attribute_name', 100)->default('Pack');
                $table->string('attribute_value', 100);
                $table->decimal('price', 10, 2)->default(0);
                $table->decimal('weight', 8, 2)->nullable();
                $table->unsignedInteger('stock_quantity')->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('image_path', 500)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_tag')) {
            Schema::create('product_tag', function (Blueprint $table) {
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->primary(['product_id', 'tag_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};

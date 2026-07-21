<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('homepage_settings');

        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                foreach ([
                    'home_banner_image_1',
                    'home_banner_image_2',
                    'home_banner_image_3',
                    'home_banner_image_4',
                ] as $column) {
                    if (Schema::hasColumn('site_settings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('homepage_settings')) {
            Schema::create('homepage_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('hero_enabled')->default(true);
                $table->boolean('hero_slider_enabled')->default(false);
                $table->json('hero_slides')->nullable();
                $table->string('why_bharat_title')->nullable();
                $table->json('why_bharat_items')->nullable();
                $table->string('what_we_do_title')->nullable();
                $table->text('what_we_do_description')->nullable();
                $table->string('what_we_do_label')->nullable();
                $table->string('what_we_do_image_url')->nullable();
                $table->json('what_we_do_items')->nullable();
                $table->string('who_we_serve_title')->nullable();
                $table->json('who_we_serve_items')->nullable();
                $table->string('key_highlights_title')->nullable();
                $table->string('key_highlights_subtitle')->nullable();
                $table->json('key_highlights_items')->nullable();
                $table->boolean('video_reviews_enabled')->default(true);
                $table->string('video_reviews_title')->nullable();
                $table->text('video_reviews_subtitle')->nullable();
                $table->json('video_reviews_items')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('site_settings')) {
            Schema::table('site_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('site_settings', 'home_banner_image_1')) {
                    $table->string('home_banner_image_1')->nullable();
                }
                if (! Schema::hasColumn('site_settings', 'home_banner_image_2')) {
                    $table->string('home_banner_image_2')->nullable();
                }
                if (! Schema::hasColumn('site_settings', 'home_banner_image_3')) {
                    $table->string('home_banner_image_3')->nullable();
                }
                if (! Schema::hasColumn('site_settings', 'home_banner_image_4')) {
                    $table->string('home_banner_image_4')->nullable();
                }
            });
        }
    }
};

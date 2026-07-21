<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->string('stats_background_image')->nullable()->after('why_items');
            $table->json('stats_items')->nullable()->after('stats_background_image');
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'stats_background_image',
                'stats_items',
            ]);
        });
    }
};

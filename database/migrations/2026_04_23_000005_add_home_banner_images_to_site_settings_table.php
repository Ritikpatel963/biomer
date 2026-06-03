<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('home_banner_image_1')->nullable()->after('instagram_embed_code');
            $table->string('home_banner_image_2')->nullable()->after('home_banner_image_1');
            $table->string('home_banner_image_3')->nullable()->after('home_banner_image_2');
            $table->string('home_banner_image_4')->nullable()->after('home_banner_image_3');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'home_banner_image_1',
                'home_banner_image_2',
                'home_banner_image_3',
                'home_banner_image_4',
            ]);
        });
    }
};


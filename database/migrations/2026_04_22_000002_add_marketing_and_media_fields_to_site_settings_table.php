<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('google_analytics_id')->nullable()->after('linkedin_url');
            $table->string('homepage_video_url')->nullable()->after('google_analytics_id');
            $table->string('homepage_video_title')->nullable()->after('homepage_video_url');
            $table->text('homepage_video_caption')->nullable()->after('homepage_video_title');
            $table->text('instagram_embed_code')->nullable()->after('homepage_video_caption');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'google_analytics_id',
                'homepage_video_url',
                'homepage_video_title',
                'homepage_video_caption',
                'instagram_embed_code',
            ]);
        });
    }
};

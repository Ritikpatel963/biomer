<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->string('story_heading')->nullable()->after('stats_items');
            $table->text('story_paragraph')->nullable()->after('story_heading');
            $table->string('story_button_text')->nullable()->after('story_paragraph');
            $table->string('story_button_url')->nullable()->after('story_button_text');
            $table->json('story_items')->nullable()->after('story_button_url');
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'story_heading',
                'story_paragraph',
                'story_button_text',
                'story_button_url',
                'story_items',
            ]);
        });
    }
};

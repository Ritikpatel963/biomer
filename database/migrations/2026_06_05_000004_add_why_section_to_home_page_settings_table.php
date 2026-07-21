<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->string('why_heading')->nullable()->after('solution_items');
            $table->text('why_paragraph')->nullable()->after('why_heading');
            $table->json('why_items')->nullable()->after('why_paragraph');
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'why_heading',
                'why_paragraph',
                'why_items',
            ]);
        });
    }
};

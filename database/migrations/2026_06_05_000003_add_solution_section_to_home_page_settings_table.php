<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->string('solution_heading')->nullable()->after('problem_items');
            $table->text('solution_paragraph')->nullable()->after('solution_heading');
            $table->json('solution_items')->nullable()->after('solution_paragraph');
        });
    }

    public function down(): void
    {
        Schema::table('home_page_settings', function (Blueprint $table) {
            $table->dropColumn([
                'solution_heading',
                'solution_paragraph',
                'solution_items',
            ]);
        });
    }
};

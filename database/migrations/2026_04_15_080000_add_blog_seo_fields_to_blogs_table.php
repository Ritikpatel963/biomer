<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('author')->nullable()->after('thumbnail');
            $table->unsignedSmallInteger('reading_time')->default(5)->after('author');
            $table->string('meta_title')->nullable()->after('status');
            $table->string('meta_tags')->nullable()->after('meta_title');
            $table->text('meta_description')->nullable()->after('meta_tags');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['author', 'reading_time', 'meta_title', 'meta_tags', 'meta_description']);
        });
    }
};

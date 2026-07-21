<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('author_bio')->nullable()->after('author');
            $table->timestamp('published_at')->nullable()->after('status')->index();
        });

        DB::table('blogs')
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['published_at']);
            $table->dropColumn(['author_bio', 'published_at']);
        });
    }
};

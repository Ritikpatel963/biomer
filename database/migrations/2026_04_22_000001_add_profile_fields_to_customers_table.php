<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('audience_type')->nullable()->after('phone');
            $table->text('address')->nullable()->after('audience_type');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 20)->nullable()->after('state');
            $table->string('country')->nullable()->after('pincode');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'audience_type',
                'address',
                'city',
                'state',
                'pincode',
                'country',
            ]);
        });
    }
};

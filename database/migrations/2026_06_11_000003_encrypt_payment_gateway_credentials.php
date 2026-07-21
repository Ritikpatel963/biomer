<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->text('api_key')->nullable()->change();
            $table->text('secret_key')->nullable()->change();
        });

        DB::table('payment_gateways')
            ->select(['id', 'api_key', 'secret_key'])
            ->orderBy('id')
            ->each(function ($gateway): void {
                $updates = [];

                foreach (['api_key', 'secret_key'] as $column) {
                    $value = $gateway->{$column};

                    if ($value === null) {
                        continue;
                    }

                    if ($value === '') {
                        $updates[$column] = null;
                        continue;
                    }

                    if ($this->isEncryptedString($value)) {
                        continue;
                    }

                    $updates[$column] = Crypt::encryptString($value);
                }

                if ($updates !== []) {
                    DB::table('payment_gateways')
                        ->where('id', $gateway->id)
                        ->update($updates);
                }
            });
    }

    public function down(): void
    {
        // Keep credentials encrypted at rest; do not restore plaintext secrets.
    }

    private function isEncryptedString(string $value): bool
    {
        try {
            Crypt::decryptString($value);
            return true;
        } catch (Throwable) {
            return false;
        }
    }
};

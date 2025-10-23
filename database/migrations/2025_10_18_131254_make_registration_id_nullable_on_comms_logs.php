<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comms_logs', function (Blueprint $table) {
            // Kalau sebelumnya ada FK, lepas dulu
            try { $table->dropForeign(['registration_id']); } catch (\Throwable $e) {}

            $table->unsignedBigInteger('registration_id')->nullable()->change();

            // Pasang lagi FK yang elegan
            $table->foreign('registration_id')
                  ->references('id')->on('registrations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comms_logs', function (Blueprint $table) {
            try { $table->dropForeign(['registration_id']); } catch (\Throwable $e) {}
            $table->unsignedBigInteger('registration_id')->nullable(false)->change();
            $table->foreign('registration_id')
                  ->references('id')->on('registrations')
                  ->cascadeOnDelete();
        });
    }
};

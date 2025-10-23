<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $t) {
            if (!Schema::hasColumn('campaigns','name')) {
                $t->string('name')->after('id');
            }
            if (!Schema::hasColumn('campaigns','text_template')) {
                $t->text('text_template')->nullable()->after('name');
            }
            if (!Schema::hasColumn('campaigns','delay_ms')) {
                $t->unsignedInteger('delay_ms')->default(0)->after('throttle_per_second');
            }
        });

        // Backfill: isi name = key untuk data lama
        DB::table('campaigns')->whereNull('name')->orWhere('name','')->update([
            'name' => DB::raw('`key`')
        ]);
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $t) {
            if (Schema::hasColumn('campaigns','delay_ms')) $t->dropColumn('delay_ms');
            if (Schema::hasColumn('campaigns','text_template')) $t->dropColumn('text_template');
            if (Schema::hasColumn('campaigns','name')) $t->dropColumn('name');
        });
    }
};

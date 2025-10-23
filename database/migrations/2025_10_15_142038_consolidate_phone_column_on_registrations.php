<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $t) {
            // tambahkan kolom baru sementara nullable supaya bisa diisi dulu
            $t->string('phone')->nullable()->after('name');
            $t->index('phone');
        });

        // Backfill & normalisasi
        $normalizer = function (string $raw = null): ?string {
            if (!$raw) return null;
            $digits = preg_replace('/\D+/', '', $raw);
            if ($digits === '') return null;
            // ubah 0xxxxxxxxx jadi 62xxxxxxxxx
            if (str_starts_with($digits, '0')) {
                $digits = '62' . substr($digits, 1);
            }
            // buang plus jika ada
            if (str_starts_with($digits, '62') === false) {
                // kalau orang iseng masukin 8xxxx, tetap prefiks 62
                if (str_starts_with($digits, '8')) {
                    $digits = '62' . $digits;
                }
            }
            return $digits;
        };

        DB::table('registrations')
            ->select('id', 'phone', 'phone_raw', 'phone_e164')
            ->orderBy('id')
            ->chunkById(1000, function ($rows) use ($normalizer) {
                foreach ($rows as $r) {
                    $src = $r->phone ?? $r->phone_e164 ?? $r->phone_raw ?? null;
                    $norm = $normalizer($src);
                    DB::table('registrations')->where('id', $r->id)->update(['phone' => $norm]);
                }
            });

        // Pastikan tidak ada null
        DB::table('registrations')->whereNull('phone')->update(['phone' => '620']); // dummy minimal supaya NOT NULL bisa lewat

        // Kunci: jadikan NOT NULL + UNIQUE (tanpa install dbal, pakai raw SQL)
        // Sesuaikan panjang VARCHAR kalau perlu
        DB::statement("ALTER TABLE registrations MODIFY phone VARCHAR(32) NOT NULL");

        // Unique index (kalau sudah ada duplikat, ini akan gagal: bereskan dulu datanya)
        Schema::table('registrations', function (Blueprint $t) {
            $t->unique('phone');
        });

        // Hapus kolom lama
        Schema::table('registrations', function (Blueprint $t) {
            if (Schema::hasColumn('registrations','phone_raw')) $t->dropColumn('phone_raw');
            if (Schema::hasColumn('registrations','phone_e164')) $t->dropColumn('phone_e164');
        });
    }

    public function down(): void
    {
        // Balikkan: tambahkan kolom lama, isi dari phone, lalu hapus phone
        Schema::table('registrations', function (Blueprint $t) {
            $t->string('phone_raw')->nullable()->after('name');
            $t->string('phone_e164')->nullable()->after('phone_raw');
        });

        DB::table('registrations')->select('id','phone')->orderBy('id')->chunkById(1000, function ($rows) {
            foreach ($rows as $r) {
                $raw = preg_replace('/^\+?62/', '0', (string) $r->phone);
                DB::table('registrations')->where('id', $r->id)->update([
                    'phone_raw'  => $raw,
                    'phone_e164' => '+'.$r->phone,
                ]);
            }
        });

        Schema::table('registrations', function (Blueprint $t) {
            $t->dropUnique(['phone']);
            $t->dropIndex(['phone']);
            $t->dropColumn('phone');
        });
    }
};

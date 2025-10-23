<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Support\Settings;

Schedule::call(function () {
    $days = (int) Settings::get('retention_days', 90);
    if ($days <= 0) return;

    $cutoff = now()->subDays($days);

    DB::transaction(function () use ($cutoff) {
        DB::table('comms_logs')->where('created_at', '<', $cutoff)->delete();
        // Hapus registrations lama, tickets ikut kehapus via FK cascade
        DB::table('registrations')->where('created_at', '<', $cutoff)->delete();
    });
})->name('cleanup:retention')->dailyAt('02:30')->onOneServer()->withoutOverlapping();

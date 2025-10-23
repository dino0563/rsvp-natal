<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as Base;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Support\Settings as AppSettings;

class OverviewStats extends Base
{
    protected function getStats(): array
    {
        $total = Registration::count();
        $sent  = Registration::where('wa_last_status', 'sent')->count();
        $deliv = Registration::whereIn('wa_last_status', ['delivered', 'read'])->count();
        $used  = Ticket::whereNotNull('used_at')->count();

        // pakai default 0 kalau key 'capacity' belum ada
        $cap   = (int) AppSettings::get('capacity', 0);
        $sisa  = max($cap - $total, 0);

        return [
            Stat::make('RSVP', $total),
            Stat::make('WA Terkirim', $sent),
            Stat::make('Delivered/Read', $deliv),
            Stat::make('Hadir', $used),
            Stat::make('Kapasitas sisa', $sisa),
        ];
    }
}

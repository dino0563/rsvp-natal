<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    // full width di grid 12 kolom
    protected int|string|array $columnSpan = ['lg' => 6];

    // 5 kolom di layar besar biar tidak “gantung” satu kotak
    protected function getColumns(): int|array
    {
        return [
            'md' => 1,
            'lg' => 5,
        ];
    }


    protected function getStats(): array
    {
        $total = Registration::count();
        $sent = Registration::where('wa_last_status', 'sent')->count();
        $deliv = Registration::where('wa_last_status', 'delivered')->count();
        $read = Registration::where('wa_last_status', 'read')->count();
        $used = Ticket::whereNotNull('used_at')->count();

        return [
            Stat::make('RSVP', $total)->icon('heroicon-o-users')->color('primary'),
            Stat::make('WA Terkirim', $sent)->icon('heroicon-o-paper-airplane')->color('warning'),
            Stat::make('Delivered', $deliv)->icon('heroicon-o-inbox-arrow-down')->color('success'),
            Stat::make('Read', $read)->icon('heroicon-o-eye')->color('success'),
            Stat::make('Hadir', $used)->icon('heroicon-o-check-badge')->color('success'),
        ];

    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class FunnelWidget extends ChartWidget
{
    protected ?string $heading = 'Funnel RSVP → Tiket → WA → Hadir';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $submit     = Registration::count();
        $ticketSent = Registration::whereIn('wa_last_status', ['sent','delivered','read'])->count();
        $delivered  = Registration::where('wa_last_status', 'delivered')->count();
        $read       = Registration::where('wa_last_status', 'read')->count();
        $hadir      = Ticket::whereNotNull('used_at')->count();

        return [
            'datasets' => [[
                'label' => 'Jumlah',
                'data'  => [$submit, $ticketSent, $delivered, $read, $hadir],
            ]],
            'labels' => ['Submit','Ticket Sent','Delivered','Read','Hadir'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    // Biar horizontal, kayak funnel beneran
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => ['mode' => 'nearest'],
            ],
            'scales' => [
                'x' => ['beginAtZero' => true],
            ],
        ];
    }
}

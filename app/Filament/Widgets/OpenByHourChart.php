<?php

namespace App\Filament\Widgets;

use App\Models\CommsLog;
use Filament\Widgets\ChartWidget;

class OpenByHourChart extends ChartWidget
{
    protected ?string $heading = 'Jam paling rame open WA'; // non-static
    protected int|string|array $columnSpan = ['lg' => 6];

    protected function getData(): array
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $buckets = array_fill(0, 24, 0);

        CommsLog::query()
            ->where('channel', 'wa')
            ->where('status', 'read')
            ->select('id', 'created_at')     // penting
            ->orderBy('id')
            ->chunkById(2000, function ($rows) use (&$buckets, $tz) {
                foreach ($rows as $log) {
                    $h = (int) $log->created_at->timezone($tz)->format('G');
                    $buckets[$h]++;
                }
            });

        return [
            'datasets' => [
                [
                    'label' => 'Open WA per jam',
                    'data' => array_values($buckets),
                ]
            ],
            'labels' => array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', array_keys($buckets)),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

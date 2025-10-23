<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Widgets\ChartWidget;

class SubmitByHourChart extends ChartWidget
{
    protected ?string $heading = 'Jam paling rame submit'; // non-static
    protected int|string|array $columnSpan = ['lg' => 6];

    protected function getData(): array
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $buckets = array_fill(0, 24, 0);

        Registration::query()
            ->select('id', 'created_at')           // penting untuk chunkById
            ->orderBy('id')
            ->chunkById(2000, function ($rows) use (&$buckets, $tz) {
                foreach ($rows as $r) {
                    $dt = $r->created_at?->timezone($tz);
                    if ($dt) {
                        $h = (int) $dt->format('G'); // 0..23
                        $buckets[$h]++;
                    }
                }
            });

        return [
            'datasets' => [[
                'label' => 'Submit per jam',
                'data'  => $buckets,
            ]],
            'labels' => array_map(
                fn ($h) => str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':00',
                range(0, 23)
            ),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

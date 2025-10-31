<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    // Menerima props dari Page
    /** @var array<string,mixed> */
    public array $filters = [];

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'lg' => 3,
            'xl'=> 5
        ];
    }

    protected function getStats(): array
    {
        // Resolve date window dari filters
        [$from, $to, $days] = $this->resolveDateWindow($this->filters);

        $sentStages      = ['sent', 'delivered', 'read'];
        $deliveredStages = ['delivered', 'read'];
        $readStages      = ['read'];

        // Base constraints dari filters non-tanggal
        $regBase = Registration::query();
        $tikBase = Ticket::query();

        if (!empty($this->filters['church'])) {
            $regBase->where('church', $this->filters['church']);
        }
        if (!empty($this->filters['channel'])) {
            $regBase->where('channel', $this->filters['channel']);
        }
        if (!empty($this->filters['wa_status']) && is_array($this->filters['wa_status'])) {
            $regBase->whereIn('wa_last_status', $this->filters['wa_status']);
        }
        if (!empty($this->filters['ticket_status'])) {
            if ($this->filters['ticket_status'] === 'sent') {
                $tikBase->whereNotNull('sent_at');
            } elseif ($this->filters['ticket_status'] === 'pending') {
                $tikBase->whereNull('sent_at');
            }
        }

        // Total kumulatif TERFILTER
        $registeredTotal  = (clone $regBase)->count();
        $waSentTotal      = (clone $regBase)->whereIn('wa_last_status', $sentStages)->count();
        $waDeliveredTotal = (clone $regBase)->whereIn('wa_last_status', $deliveredStages)->count();
        $waReadTotal      = (clone $regBase)->whereIn('wa_last_status', $readStages)->count();

        $checkinTotal     = (clone $tikBase)->whereNotNull('used_at')->count();

        // Delta "kemarin" atau "hari terakhir dalam window"
        // Jika preset = today/7d/custom, kita jadikan delta = jumlah pada hari terakhir window-1
        $yStart = (clone $to)->copy()->subDay()->startOfDay();
        $yEnd   = (clone $to)->copy()->subDay()->endOfDay();

        $registeredYesterday  = (clone $regBase)->whereBetween('created_at', [$yStart, $yEnd])->count();
        $waSentYesterday      = (clone $regBase)->whereIn('wa_last_status', $sentStages)->whereBetween('updated_at', [$yStart, $yEnd])->count();
        $waDeliveredYesterday = (clone $regBase)->whereIn('wa_last_status', $deliveredStages)->whereBetween('updated_at', [$yStart, $yEnd])->count();
        $waReadYesterday      = (clone $regBase)->whereIn('wa_last_status', $readStages)->whereBetween('updated_at', [$yStart, $yEnd])->count();
        $checkinYesterday     = (clone $tikBase)->whereNotNull('used_at')->whereBetween('used_at', [$yStart, $yEnd])->count();

        // Helpers
        $fmt   = fn (int $n): string => number_format($n);
        $pct   = fn (int $num, int $den): string => $den > 0 ? number_format($num / $den * 100, 1) . '%' : '0%';
        $delta = fn (int $n): string => ($n >= 0 ? '+' : '') . $n;

        $descIcon = function (int $d): string {
            if ($d > 0) return 'heroicon-o-arrow-trending-up';
            if ($d < 0) return 'heroicon-o-arrow-trending-down';
            return 'heroicon-o-minus';
        };

        $descColor = function (int $d): string {
            if ($d > 0) return 'success';
            if ($d < 0) return 'danger';
            return 'secondary';
        };

        // Series harian (jumlah per hari di window), pakai field timestamp yang masuk akal per tahap
        $seriesRegistered = $this->dailySeries($from, $days, function (Carbon $start, Carbon $end) use ($regBase) {
            return (clone $regBase)->whereBetween('created_at', [$start, $end])->count();
        });

        $seriesSent = $this->dailySeries($from, $days, function (Carbon $start, Carbon $end) use ($regBase, $sentStages) {
            return (clone $regBase)->whereIn('wa_last_status', $sentStages)->whereBetween('updated_at', [$start, $end])->count();
        });

        $seriesDelivered = $this->dailySeries($from, $days, function (Carbon $start, Carbon $end) use ($regBase, $deliveredStages) {
            return (clone $regBase)->whereIn('wa_last_status', $deliveredStages)->whereBetween('updated_at', [$start, $end])->count();
        });

        $seriesRead = $this->dailySeries($from, $days, function (Carbon $start, Carbon $end) use ($regBase, $readStages) {
            return (clone $regBase)->whereIn('wa_last_status', $readStages)->whereBetween('updated_at', [$start, $end])->count();
        });

        $seriesCheckin = $this->dailySeries($from, $days, function (Carbon $start, Carbon $end) use ($tikBase) {
            return (clone $tikBase)->whereNotNull('used_at')->whereBetween('used_at', [$start, $end])->count();
        });

        return [
            Stat::make('Registered', $fmt($registeredTotal))
                ->icon('heroicon-o-users')
                ->color('primary')
                ->description($delta($registeredYesterday) . ' di hari terakhir')
                ->descriptionIcon($descIcon($registeredYesterday))
                ->descriptionColor($descColor($registeredYesterday))
                ->chart($seriesRegistered),

            Stat::make('WA Terkirim', $fmt($waSentTotal))
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->description($pct($waSentTotal, $registeredTotal) . ' | ' . $delta($waSentYesterday))
                ->descriptionIcon($descIcon($waSentYesterday))
                ->descriptionColor($descColor($waSentYesterday))
                ->chart($seriesSent),

            Stat::make('Delivered', $fmt($waDeliveredTotal))
                ->icon('heroicon-o-truck')
                ->color('info')
                ->description($pct($waDeliveredTotal, $waSentTotal) . ' | ' . $delta($waDeliveredYesterday))
                ->descriptionIcon($descIcon($waDeliveredYesterday))
                ->descriptionColor($descColor($waDeliveredYesterday))
                ->chart($seriesDelivered),

            Stat::make('Read', $fmt($waReadTotal))
                ->icon('heroicon-o-eye')
                ->color('success')
                ->description($pct($waReadTotal, $waDeliveredTotal) . ' | ' . $delta($waReadYesterday))
                ->descriptionIcon($descIcon($waReadYesterday))
                ->descriptionColor($descColor($waReadYesterday))
                ->chart($seriesRead),

            Stat::make('Check-in', $fmt($checkinTotal))
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->description($pct($checkinTotal, $waReadTotal) . ' | ' . $delta($checkinYesterday))
                ->descriptionIcon($descIcon($checkinYesterday))
                ->descriptionColor($descColor($checkinYesterday))
                ->chart($seriesCheckin),
        ];
    }

    /** @return array{0:Carbon,1:Carbon,2:int} */
    private function resolveDateWindow(array $filters): array
    {
        $todayEnd = Carbon::today()->endOfDay();
        $preset = $filters['date_preset'] ?? 'today';

        if ($preset === '7d') {
            $from = Carbon::today()->subDays(6)->startOfDay();
            $to   = $todayEnd;
        } elseif ($preset === 'custom' && !empty($filters['start_date']) && !empty($filters['end_date'])) {
            $from = Carbon::parse($filters['start_date'])->startOfDay();
            $to   = Carbon::parse($filters['end_date'])->endOfDay();
        } else {
            // default: today
            $from = Carbon::today()->startOfDay();
            $to   = $todayEnd;
        }

        $days = max(1, $from->diffInDays($to) + 1);

        return [$from, $to, $days];
    }

    /**
     * @param \Closure(Carbon $start, Carbon $end): int $counter
     * @return array<int,int>
     */
    private function dailySeries(Carbon $from, int $days, \Closure $counter): array
    {
        $series = [];

        for ($i = 0; $i < $days; $i++) {
            $start = (clone $from)->addDays($i)->startOfDay();
            $end   = (clone $from)->addDays($i)->endOfDay();
            $series[] = (int) $counter($start, $end);
        }

        return $series;
    }
}

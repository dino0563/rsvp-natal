<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class Reports extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    // Nav stuff
    protected static string|UnitEnum|null $navigationGroup = 'Keamanan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Laporan';

    // View untuk kontennya

    public function getView(): string
    {
        return 'filament.pages.reports';
    }

    // State filter untuk form Schemas
    public ?array $filters = [
        'from' => null,
        'to' => null,
        'dimension' => 'education_level',
    ];

    public function mount(): void
    {
        // WAJIB: init form state
        $this->form->fill($this->filters);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter')->schema([
                    Grid::make(12)->schema([
                        DatePicker::make('from')->label('Dari tanggal')->native(false)->columnSpan(3),

                        DatePicker::make('to')->label('Sampai tanggal')->native(false)->columnSpan(3),

                        Select::make('dimension')
                            ->label('Segmentasi')
                            ->options([
                                'education_level' => 'Jenjang',
                                'church' => 'Gereja',
                                'source' => 'Referer/Source',
                            ])
                            ->native(false)
                            ->columnSpan(3),
                    ]),
                ]),
            ])
            // simpan state ke $this->filters
            ->statePath('filters');
    }

    protected function getHeaderActions(): array
{
    return [
        Action::make('exportSegment')
            ->label('Export segmented CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $f = $this->form->getState();
                $from = $f['from'] ? (string) $f['from'] : null;
                $to   = $f['to']   ? (string) $f['to']   : null;

                // whitelist dimension biar aman
                $dim  = in_array($f['dimension'] ?? 'education_level', ['education_level','church','source'], true)
                    ? $f['dimension']
                    : 'education_level';

                $filename = "seg-{$dim}-" . now()->format('Ymd-His') . '.csv';

                return Response::streamDownload(function () use ($from, $to, $dim) {
                    // 1) Base agregat TANPA order by
                    $base = DB::table('registrations')
                        ->leftJoin('tickets', 'tickets.registration_id', '=', 'registrations.id')
                        ->when($from, fn($qq) => $qq->where('registrations.created_at', '>=', $from . ' 00:00:00'))
                        ->when($to,   fn($qq) => $qq->where('registrations.created_at', '<=', $to . ' 23:59:59'))
                        ->selectRaw("COALESCE(NULLIF(registrations.$dim,''), '—') as category")
                        ->selectRaw('COUNT(registrations.id) as total')
                        ->selectRaw('SUM(CASE WHEN tickets.used_at IS NOT NULL THEN 1 ELSE 0 END) as hadir')
                        ->groupBy('category');

                    // 2) Bungkus sebagai subquery → urutkan hanya pakai kolom agregat
                    $rows = DB::query()
                        ->fromSub($base, 'agg')
                        ->orderByDesc('hadir')  // atau 'total', suka-suka kamu
                        ->orderByDesc('total')  // tie-breaker yang masih agregat
                        ->orderBy('category')   // terakhir biar deterministik
                        ->get();

                    // 3) Tulis CSV
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['category','total','hadir','rate']);
                    foreach ($rows as $row) {
                        $rate = $row->total > 0 ? round($row->hadir / $row->total * 100, 2) : 0;
                        fputcsv($out, [$row->category, $row->total, $row->hadir, $rate.'%']);
                    }
                    fclose($out);
                }, $filename, ['Content-Type' => 'text/csv']);
            }),
    ];
}


    // v4: pakai header/footer widgets, bukan getWidgets()
    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\FunnelWidget::class,
            \App\Filament\Widgets\AttendanceByCategoryTable::class
        ];
    }
}

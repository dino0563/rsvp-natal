<?php

namespace App\Filament\Pages;

use App\Jobs\SendTicketWaJob;
use App\Models\Registration;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use UnitEnum;
use App\Filament\Widgets\DashboardStats;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class Dashboard extends BaseDashboard implements HasSchemas
{

    use InteractsWithSchemas, HasPageShield;
    protected static string|UnitEnum|null $navigationGroup = 'Operasional';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'Dashboard';

    // protected static ?string $maxContentWidth = '7xl';
    // Grid 12 kolom biar gampang atur span widget


    public function getColumns(): int|array
    {
        return [
            'md' => 1,
            'lg' => 12,
        ];
    }

    /** @var array<string,mixed> */
    public array $filters = [];

    public function mount(): void
    {
        $this->filters = [
            'date_preset'  => 'today',   // today | 7d | custom
            'start_date'   => null,      // required if custom
            'end_date'     => null,
            'church'       => null,      // gereja
            'channel'      => null,
            'wa_status'    => [],        // ['sent','delivered','read','failed']
            'ticket_status' => null,      // sent | pending | null
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Export CSV cepat
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $filename = 'registrations-' . now()->format('Ymd-His') . '.csv';

                    return Response::streamDownload(function () {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, [
                            'id',
                            'name',
                            'phone',
                            'age',
                            'education_level',
                            'school',
                            'church',
                            'status_ticket',
                            'wa_last_status',
                            'created_at'
                        ]);

                        foreach (
                            Registration::select([
                                'id',
                                'name',
                                'phone',
                                'age',
                                'education_level',
                                'school',
                                'church',
                                'status_ticket',
                                'wa_last_status',
                                'created_at'
                            ])->cursor() as $r
                        ) {
                            fputcsv($out, [
                                $r->id,
                                $r->name,
                                $r->phone,
                                $r->age,
                                $r->education_level,
                                $r->school,
                                $r->church,
                                $r->status_ticket->value ?? $r->status_ticket,
                                $r->wa_last_status->value ?? $r->wa_last_status,
                                $r->created_at?->timezone(config('app.timezone', 'Asia/Jakarta'))?->format('Y-m-d H:i:s'),
                            ]);
                        }

                        fclose($out);
                    }, $filename, ['Content-Type' => 'text/csv']);
                }),
        ];
    }

    public function getHeaderWidgets(): array
    {
        // Pass seluruh filters ke widget
        return [
            DashboardStats::make([
                'filters' => $this->filters,
            ]),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Segmentation & Filters')
                    ->schema([
                        Grid::make(12)->schema([

                            // WAS: Select::make('filters.date_preset')
                            Select::make('date_preset')
                                ->label('Date range')
                                ->options([
                                    'today'  => 'Today',
                                    '7d'     => 'Last 7 days',
                                    'custom' => 'Custom',
                                ])
                                ->native(false)
                                ->live()
                                ->columnSpan(3),

                            // WAS: DatePicker::make('filters.start_date')
                            DatePicker::make('start_date')
                                ->label('Start')
                                ->native(false)
                                ->visible(fn($get) => $get('date_preset') === 'custom')
                                ->columnSpan(2),

                            // WAS: DatePicker::make('filters.end_date')
                            DatePicker::make('end_date')
                                ->label('End')
                                ->native(false)
                                ->visible(fn($get) => $get('date_preset') === 'custom')
                                ->columnSpan(2),

                            // WAS: Select::make('filters.church')
                            Select::make('church')
                                ->label('Gereja')
                                ->options(fn() => Registration::query()
                                    ->whereNotNull('church')
                                    ->distinct()
                                    ->orderBy('church')
                                    ->pluck('church', 'church')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->columnSpan(2),

                            // WAS: Select::make('filters.channel')
                            Select::make('channel')
                                ->label('Channel')
                                ->options(fn() => Registration::query()
                                    ->whereNotNull('channel')
                                    ->distinct()
                                    ->orderBy('channel')
                                    ->pluck('channel', 'channel')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->columnSpan(3),

                            // WAS: Select::make('filters.wa_status')
                            Select::make('wa_status')
                                ->label('Status WA')
                                ->options([
                                    'sent'      => 'Sent',
                                    'delivered' => 'Delivered',
                                    'read'      => 'Read',
                                    'failed'    => 'Failed',
                                ])
                                ->multiple()
                                ->native(false)
                                ->columnSpan(3),

                            // WAS: Select::make('filters.ticket_status')
                            Select::make('ticket_status')
                                ->label('Status Tiket')
                                ->options([
                                    'sent'    => 'Sent',
                                    'pending' => 'Pending',
                                ])
                                ->native(false)
                                ->columnSpan(2),
                        ]),
                    ]),
            ])
            ->statePath('filters'); // ini tetap
    }



    public function getWidgets(): array
    {
        return [
            // \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\SubmitByHourChart::class,
            \App\Filament\Widgets\OpenByHourChart::class,
            \App\Filament\Widgets\QuickLookRegistrations::class,

        ];
    }
}

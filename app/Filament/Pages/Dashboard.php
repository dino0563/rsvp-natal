<?php

namespace App\Filament\Pages;

use App\Jobs\SendTicketWaJob;
use App\Models\Registration;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|UnitEnum|null $navigationGroup = 'Operasional';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'Dashboard';

    // Grid 12 kolom biar gampang atur span widget
    public function getColumns(): int|array
    {
        return [
            'md' => 1,
            'lg' => 12,
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
                            'id','name','phone','age','education_level','school','church',
                            'status_ticket','wa_last_status','created_at'
                        ]);

                        foreach (Registration::select([
                            'id','name','phone','age','education_level','school','church',
                            'status_ticket','wa_last_status','created_at'
                        ])->cursor() as $r) {
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

            // Blast T-1 (pakai command campaign:run T-1)
            Action::make('blastT1')
                ->label('Blast T-1')
                ->icon('heroicon-o-bolt')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    Artisan::call('campaign:run', ['key' => 'T-1']);
                    Notification::make()->title('Blast T-1 dijadwalkan')->success()->send();
                }),

            // Resend untuk yang gagal/blocked
            Action::make('resendFailed')
                ->label('Resend gagal')
                ->icon('heroicon-o-paper-airplane')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    $count = 0;

                    Registration::query()
                        ->whereIn('wa_last_status', ['failed', 'blocked'])
                        ->select('id')                       // cukup id saja, lebih ringan
                        ->orderBy('id')
                        ->chunkById(500, function ($rows) use (&$count) {
                            foreach ($rows as $r) {
                                SendTicketWaJob::dispatch($r->id);
                                $count++;
                            }
                        });

                    Notification::make()
                        ->title("Resend dijadwalkan untuk {$count} orang")
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStats::class,
            \App\Filament\Widgets\SubmitByHourChart::class,
            \App\Filament\Widgets\OpenByHourChart::class,
        ];
    }
}

<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Resources\Registrations\Schemas\RegistrationForm;
use App\Filament\Resources\Registrations\Tables\RegistrationsTable;
use App\Models\Registration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;
use App\Jobs\SendTicketWaJob;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Size;
use UnitEnum;




class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;
    protected static string|UnitEnum|null $navigationGroup = 'Operasional';
    protected static ?string $navigationLabel = 'Registrasi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;



    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->label('Phone Number'),
                TextColumn::make('education_level')->label('Jenjang')->sortable(),
                TextColumn::make('church')->sortable(),
                BadgeColumn::make('status_ticket')->colors([
                    'warning' => 'generated',
                    'success' => 'sent',
                    'info' => 'used',
                    'danger' => 'revoked',
                ]),
                BadgeColumn::make('wa_last_status')->colors([
                    'success' => ['delivered', 'read'],
                    'warning' => 'sent',
                    'danger' => ['failed', 'blocked'],
                ])->label('Status WA'),
                TextColumn::make('created_at')->dateTime('d M H:i')->label('Daftar'),
            ])
            ->filters([
                SelectFilter::make('status_ticket')->options([
                    'pending' => 'pending',
                    'generated' => 'generated',
                    'sent' => 'sent',
                    'used' => 'used',
                    'revoked' => 'revoked',
                ]),
                SelectFilter::make('wa_last_status')->options([
                    'queued' => 'queued',
                    'sent' => 'sent',
                    'delivered' => 'delivered',
                    'read' => 'read',
                    'failed' => 'failed',
                    'blocked' => 'blocked',
                ]),
                Filter::make('tanggal')
                    ->form([DatePicker::make('from'), DatePicker::make('to')])
                    ->query(
                        fn($query, array $data) => $query
                            ->when($data['from'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['to'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $data['to']))
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-m-check-badge')
                        ->requiresConfirmation()
                        ->color('success')
                        ->action(fn(Registration $record) => $record->update(['status_ticket' => 'generated'])),

                    Action::make('send')
                        ->label('Kirim Tiket WA')
                        ->icon(Heroicon::OutlinedPaperAirplane)
                        ->action(fn(Registration $record) => SendTicketWaJob::dispatch($record->id)),

                    Action::make('resend')
                        ->label('Resend')
                        ->visible(
                            fn(Registration $record) =>
                            in_array($record->wa_last_status?->value ?? $record->wa_last_status, ['failed', 'blocked'], true)
                        )
                        ->action(fn(Registration $record) => SendTicketWaJob::dispatch($record->id)),

                    Action::make('cancel')
                        ->label('Batalkan')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Registration $record) {
                            $record->update(['status_ticket' => 'revoked']);
                            optional($record->ticket)->delete();
                        }),
                ])
                    ->label('More actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(Size::Small)
                    ->color('primary')
                    ->button(),
            ])

            ->groupedBulkActions([
                BulkAction::make('export_csv')
                    ->label('Export CSV')
                    ->action(fn(Collection $records) => export_registrations_csv($records)),

                BulkAction::make('blast_T1')
                    ->label('Blast T-1')
                    ->action(fn(Collection $records) => $records->each(fn($r) => SendTicketWaJob::dispatch($r->id))),
            ]);

    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'edit' => EditRegistration::route('/{record}/edit'),
        ];
    }
}

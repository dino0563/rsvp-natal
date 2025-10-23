<?php

namespace App\Filament\Resources\Tickets;

use App\Filament\Resources\Tickets\Pages; // kalau kamu memang pakai namespace "Tickets", ini oke
use App\Http\Controllers\TicketController;
use App\Models\Ticket;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use UnitEnum; // dipakai untuk navigationGroup union type
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static string|UnitEnum|null $navigationGroup = 'Operasional';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;
    protected static ?string $navigationLabel = 'Tiket';
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            // biar nggak N+1 waktu akses relasi 'registration'
            // ->modifyQueryUsing(fn ($query) => $query->with('registration'))

            ->columns([
                TextColumn::make('code')->label('Kode')->searchable(),
                TextColumn::make('registration.name')->label('Nama')->toggleable(),
                TextColumn::make('used_at')->label('Dipakai')->since()->placeholder('—'),
                TextColumn::make('registration.ticket_url')
                    ->label('Link')
                    ->badge()
                    ->formatStateUsing(fn ($state) => filled($state) ? 'Buka' : '—')
                    ->color(fn ($state) => filled($state) ? 'success' : 'gray')
                    ->url(fn ($record) => $record->registration?->ticket_url ?: null)
                    ->openUrlInNewTab()
                    ->tooltip(fn ($record) => $record->registration?->ticket_url ?: 'Tidak ada tautan'),
            ])

            // di v4, ini tetap valid sebagai record actions
            ->recordActions([
                Action::make('regenerate')
                    ->label('Regenerate')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->button()               // render sebagai tombol
                    ->requiresConfirmation()
                    ->action(function (Ticket $t) {
                        app(TicketController::class)->regenerateQr($t);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
        ];
    }
}

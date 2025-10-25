<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\Action;


class QuickLookRegistrations extends BaseWidget
{
    protected static ?string $heading = 'Quick Look • Nama | Gereja';

    // Lebar penuh di dashboard
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Registration::query())
            ->defaultSort('id', 'desc')

            // cuma lihat-lihat, gak perlu edit
            ->paginated([8, 15, 25])
            ->paginated(8)
            ->searchable(['id', 'name', 'church'])

            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap()
                    ->grow(), // ambil ruang sisa

                Tables\Columns\TextColumn::make('church')
                    ->label('Gereja')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // Jangan arahkan klik baris ke mana-mana
            ->recordUrl(null)

            // Satu tombol ke halaman Registrations
            ->headerActions([
                Action::make('open_registrations')
                    ->label('Registrations')
                    ->icon('heroicon-o-users')
                    ->color('primary')
                    ->url(RegistrationResource::getUrl('index')),
            ])

            // Gak ada aksi baris. Biar mata istirahat.
            ->actions([])

            // Auto refresh, buat yang doyan submit massal
            ->poll('10s')
            ->striped()
            ->emptyStateHeading('Belum ada data')
            ->emptyStateDescription('Registrasi baru akan muncul di sini.');
    }
}

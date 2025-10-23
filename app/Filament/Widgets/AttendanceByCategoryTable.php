<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\Registration;

class AttendanceByCategoryTable extends BaseWidget
{
    protected static ?string $heading = 'Rasio hadir per jenjang';
    protected int|string|array $columnSpan = ['lg' => 6];

    public function table(Table $table): Table
    {
        // Group by education_level
        $query = Registration::query()
            ->leftJoin('tickets', 'tickets.registration_id', '=', 'registrations.id')
            ->selectRaw("COALESCE(NULLIF(education_level,''), '—') as category")
            ->selectRaw('COUNT(registrations.id) as total')
            ->selectRaw('SUM(CASE WHEN tickets.used_at IS NOT NULL THEN 1 ELSE 0 END) as hadir')
            ->groupBy('category')
            ->orderByDesc('hadir');

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('category')->label('Kategori')->sortable()->searchable(),
                TextColumn::make('total')->label('Total')->numeric()->sortable(),
                TextColumn::make('hadir')->label('Hadir')->numeric()->sortable(),
                TextColumn::make('rate')->label('Rate')
                    ->state(fn ($record) => $record->total > 0 ? round($record->hadir / $record->total * 100, 1).'%' : '0%')
                    ->badge()
                    ->color(fn ($record) => $record->total > 0 && ($record->hadir / $record->total) >= 0.6 ? 'success' : 'warning'),
            ])
            ->paginated(false);
    }
}

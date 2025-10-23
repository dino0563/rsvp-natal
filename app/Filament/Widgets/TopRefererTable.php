<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;

class TopRefererTable extends BaseWidget
{
    protected static ?string $heading = 'Top Referer';
    protected int|string|array $columnSpan = ['lg' => 6];

    public function table(Table $table): Table
    {
        $base = DB::table('registrations')
            ->leftJoin('tickets','tickets.registration_id','=','registrations.id')
            ->selectRaw("COALESCE(NULLIF(registrations.source,''), '—') as source")
            ->selectRaw('COUNT(registrations.id) as total')
            ->selectRaw('SUM(CASE WHEN tickets.used_at IS NOT NULL THEN 1 ELSE 0 END) as hadir')
            ->selectRaw('MIN(registrations.id) as id') // row key agregat
            ->groupBy('source');

        $query = Registration::query()->fromSub($base, 'agg');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('source')
                    ->label('Referer')
                    ->searchable(query: fn ($q, $s) => $q->where('source', 'like', "%{$s}%")),
                Tables\Columns\TextColumn::make('total')->label('Total')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('hadir')->label('Hadir')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('rate')->label('Rate')
                    ->state(fn ($r) => $r->total > 0 ? round($r->hadir / $r->total * 100, 1).'%' : '0%')
                    ->badge()
                    ->color(fn ($r) => $r->total > 0 && ($r->hadir / $r->total) >= 0.6 ? 'success' : 'warning'),
            ])
            ->defaultKeySort(false)        // kunci: jangan auto-sort pk
            ->defaultSort('total', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}

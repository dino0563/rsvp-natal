<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;

// Schemas API (v4)
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

// Form fields
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;

// Tables v4
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// Actions v4
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

// Icons v4
use Filament\Support\Icons\Heroicon;

use Illuminate\Support\Facades\Artisan;
use Filament\Resources\Resource;
use UnitEnum;
use BackedEnum;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static string|UnitEnum|null $navigationGroup = 'Engagement';

    // v4 style icon
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $navigationLabel = 'Campaigns';

    // v4: Schemas API
    public static function form(Schema $schema): Schema
    {
        return $schema->components([Section::make('Campaign')->schema([Grid::make(12)->schema([TextInput::make('name')->label('Nama Campaign')->required()->maxLength(80)->columnSpan(6), TextInput::make('throttle_per_second')->label('TPS')->numeric()->minValue(1)->maxValue(30)->default(5)->columnSpan(3), TextInput::make('delay_ms')->label('Delay per message (ms)')->numeric()->minValue(0)->maxValue(60000)->default(0)->columnSpan(3), Textarea::make('text_template')->label('Text Blast')->rows(8)->required()->columnSpan(12)->helperText('Variabel: {event_name}, {nama}, {ticket_url}, {gate_time}, {dresscode}, {code}, {phone}')])])]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([TextColumn::make('name')->label('Nama')->searchable()->sortable(), TextColumn::make('text_template')->label('View text blast')->limit(60)->tooltip(fn(Campaign $r) => $r->text_template), TextColumn::make('throttle_per_second')->label('TPS')->sortable(), TextColumn::make('delay_ms')->label('Delay (ms)')->sortable()])
            ->defaultSort('id', 'desc')

            // v4: row actions pindah ke recordActions
            ->recordActions([ViewAction::make(), EditAction::make(), Action::make('runNow')->label('Run now')->icon(Heroicon::OutlinedPlayCircle)->requiresConfirmation()->action(fn(Campaign $record) => Artisan::call('campaign:run', ['--id' => $record->id]))])

            // v4: tombol Create di headerActions
            ->headerActions([CreateAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}

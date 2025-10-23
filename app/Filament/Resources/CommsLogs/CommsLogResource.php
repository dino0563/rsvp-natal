<?php

namespace App\Filament\Resources\CommsLogs;

use App\Filament\Resources\CommsLogs\Pages\CreateCommsLog;
use App\Filament\Resources\CommsLogs\Pages\EditCommsLog;
use App\Filament\Resources\CommsLogs\Pages\ListCommsLogs;
use App\Filament\Resources\CommsLogs\Schemas\CommsLogForm;
use App\Filament\Resources\CommsLogs\Tables\CommsLogsTable;
use App\Models\CommsLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;

class CommsLogResource extends Resource
{
    protected static ?string $model = CommsLog::class;
    protected static string|UnitEnum|null $navigationGroup = 'Engagement';
    // protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'WA Logs';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function form(Schema $schema): Schema
    {
        return CommsLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('registration.name')->label('Nama')->searchable(),
            TextColumn::make('registration.phone')->label('Telepon'),
            TextColumn::make('template_key')->label('Template'),
            TextColumn::make('provider_message_id')->label('Provider ID')->wrap(),
            TextColumn::make('status')->badge()->colors([
                'success' => ['delivered', 'read', 'sent'],
                'danger' => ['failed', 'blocked'],
                'gray' => ['queued', 'unknown'],
            ]),
            TextColumn::make('created_at')->since()->label('Waktu'),
        ])->defaultSort('id', 'desc');
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
            'index' => ListCommsLogs::route('/'),
            'create' => CreateCommsLog::route('/create'),
            'edit' => EditCommsLog::route('/{record}/edit'),
        ];
    }
}

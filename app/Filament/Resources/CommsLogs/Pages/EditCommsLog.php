<?php

namespace App\Filament\Resources\CommsLogs\Pages;

use App\Filament\Resources\CommsLogs\CommsLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCommsLog extends EditRecord
{
    protected static string $resource = CommsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

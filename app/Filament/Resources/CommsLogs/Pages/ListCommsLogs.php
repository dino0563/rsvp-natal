<?php

namespace App\Filament\Resources\CommsLogs\Pages;

use App\Filament\Resources\CommsLogs\CommsLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommsLogs extends ListRecords
{
    protected static string $resource = CommsLogResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }
}

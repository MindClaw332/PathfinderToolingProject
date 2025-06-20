<?php

namespace App\Filament\Resources\CreatureResource\Pages;

use App\Filament\Resources\CreatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreatures extends ListRecords
{
    protected static string $resource = CreatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

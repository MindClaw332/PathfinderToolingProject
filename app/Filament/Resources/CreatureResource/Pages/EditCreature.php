<?php

namespace App\Filament\Resources\CreatureResource\Pages;

use App\Filament\Resources\CreatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreature extends EditRecord
{
    protected static string $resource = CreatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

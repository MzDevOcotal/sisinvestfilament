<?php

namespace App\Filament\Resources\EnfoqueResource\Pages;

use App\Filament\Resources\EnfoqueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnfoque extends EditRecord
{
    protected static string $resource = EnfoqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

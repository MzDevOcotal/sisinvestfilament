<?php

namespace App\Filament\Resources\InvestigacionResource\Pages;

use App\Filament\Resources\InvestigacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestigacion extends EditRecord
{
    protected static string $resource = InvestigacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

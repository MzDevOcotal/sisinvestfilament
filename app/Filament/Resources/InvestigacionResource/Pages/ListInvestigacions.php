<?php

namespace App\Filament\Resources\InvestigacionResource\Pages;

use App\Filament\Resources\InvestigacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvestigacions extends ListRecords
{
    protected static string $resource = InvestigacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

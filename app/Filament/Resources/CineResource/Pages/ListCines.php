<?php

namespace App\Filament\Resources\CineResource\Pages;

use App\Filament\Resources\CineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCines extends ListRecords
{
    protected static string $resource = CineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

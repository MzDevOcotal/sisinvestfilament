<?php

namespace App\Filament\Resources\InvestigacionResource\Pages;

use App\Filament\Resources\InvestigacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestigacion extends EditRecord
{
    protected static string $resource = InvestigacionResource::class;

    protected function afterSave(): void
    {
        
        $autoresIds = $this->data['autores_autores'] ?? [];
        $asesoresIds = $this->data['autores_asesores'] ?? [];

        $pivotData = collect($autoresIds)->mapWithKeys(fn ($id) => [$id => ['rol' => 'Autor']]);
        $pivotData = $pivotData->merge(collect($asesoresIds)->mapWithKeys(fn ($id) => [$id => ['rol' => 'Asesor']]));

        $this->record->autores()->sync($pivotData);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

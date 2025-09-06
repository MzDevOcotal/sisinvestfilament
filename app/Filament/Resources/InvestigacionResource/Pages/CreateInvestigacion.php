<?php

namespace App\Filament\Resources\InvestigacionResource\Pages;

use App\Filament\Resources\InvestigacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvestigacion extends CreateRecord
{
    protected static string $resource = InvestigacionResource::class;

    protected function afterCreate(): void
    {

        $autoresIds = $this->data['autores_autores'] ?? [];
        $asesoresIds = $this->data['autores_asesores'] ?? [];

        $pivotData = [];

        foreach ($autoresIds as $id) {
            $pivotData[$id] = ['rol' => 'Autor'];
        }

        foreach ($asesoresIds as $id) {
            $pivotData[$id] = ['rol' => 'Asesor'];
        }

        $this->record->autores()->sync($pivotData);
    }
}

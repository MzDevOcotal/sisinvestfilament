<?php

namespace App\Filament\Resources\InvestigacionResource\Pages;

use App\Filament\Resources\InvestigacionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\InvestigacionResource\Widgets\InvestigacionesAnuales; // Asegúrate de que esta sea la ruta correcta a tu widget


class ListInvestigacions extends ListRecords
{
    protected static string $resource = InvestigacionResource::class;
    protected static ?string $title = 'Listado de Investigaciones';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),   
            Action::make('Reporte de Investigaciones'),
        ];
    }

        protected function getHeaderWidgets(): array
    {
        return [

        ];
    }



}

<?php

namespace App\Filament\Pages;

use App\Filament\Resources\InvestigacionResource;
use App\Filament\Widgets;
use Filament\Pages\Dashboard as BasePage;
use App\Filament\Resources\InvestigacionResource\Widgets\InvestigaionesAnuales;

class Dashboard extends BasePage
{
    // Opcional: El título de la página en la barra de navegación
    protected static ?string $title = 'Dashboard';

    // Opcional: El icono de navegación en el menú lateral
    protected static ?string $navigationIcon = 'heroicon-o-home';

    // Opcional: Para controlar la ordenación en el menú lateral
    protected static ?int $navigationSort = 1;

    // Método para agregar widgets en la parte superior del Dashboard
    protected function getHeaderWidgets(): array
    {
        return [
        //
        ];
    }

    // Método para agregar widgets en la parte inferior del Dashboard
    protected function getFooterWidgets(): array
    {
        return [
            // Widgets del pie de página van aquí
        ];
    }
}
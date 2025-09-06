<?php

namespace App\Filament\Resources\InvestigacionResource\Widgets;

use Filament\Widgets\ChartWidget;

class InvestigacionesAnuales extends ChartWidget
{
    protected static ?string $heading = 'Investigaciones por Año y Área del Conocimiento';

    public ?string $filter = 'today';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Áreas del Conocimiento',
                    'data' => [2, 10, 5, 2, 21, 32, 45],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            '2021' => '2021',
            '2022' => '2022',
            '2023' => '2023',
            '2024' => '2024',
            '2025' => '2025',
        ];
    }
}

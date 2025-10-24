<?php

namespace App\Filament\Resources\InvestigacionResource\Widgets;

use App\Models\Area;
use App\Models\Investigacion;
use App\Models\Year;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class InvestigacionesAnuales extends ChartWidget
{
    protected static ?string $heading = 'Producción Científica por Año y Área del Conocimiento';
    public ?string $filter = '2024';

    protected function getData(): array
    {
        // Si no hay un filtro seleccionado, no se cargan los datos
        if (!$this->filter) {
            return [
                'datasets' => [['data' => []]],
                'labels' => [],
            ];
        }

        // Obtiene el conteo de investigaciones para el año filtrado
        $data = Investigacion::where('year_id', $this->filter)
            ->select('area_id', DB::raw('count(*) as total'))
            ->groupBy('area_id')
            ->pluck('total', 'area_id')
            ->all();

        // Obtiene las áreas correspondientes a los datos que se obtuvieron
        $areas = Area::whereIn('id', array_keys($data))
            ->pluck('name', 'id')
            ->all();

        // Prepara los datos del gráfico
        $datasets = [
            'label' => 'Total de Investigaciones',
            'data' => array_values($data),
            'backgroundColor' => [],
            'borderColor' => [],
        ];

        // Mapea los colores solo a las áreas que tienen datos
        foreach ($areas as $areaId => $areaNombre) {
            $datasets['backgroundColor'][] = $this->getBackgroundColor($areaId);
            $datasets['borderColor'][] = $this->getBackgroundColor($areaId);
        }

        return [
            'datasets' => [$datasets],
            'labels' => array_values($areas),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        $anios = Year::select('id', 'anioacademico')
            ->orderBy('anioacademico', 'asc')
            ->pluck('anioacademico', 'id')
            ->all();

        return $anios;
    }

    // Helper para asignar un color de fondo dinámico a cada área
    private function getBackgroundColor(int $areaId): string
    {
        $colores = [
            '#f87171', '#fb923c', '#facc15', '#a3e635', '#4ade80',
            '#34d399', '#22d3ee', '#38bdf8', '#60a5fa', '#818cf8',
        ];
        return $colores[($areaId - 1) % count($colores)];
    }
}
<?php

namespace App\Filament\Widgets;

use App\Models\Autor;
use App\Models\Investigacion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GlobalStatics extends BaseWidget
{
    protected function getStats(): array
    {

        // Obtener los datos reales de la tabla investigaciones
        $investigacionesPorAno = Investigacion::selectRaw('year_id, count(*) as total')
            ->where('categoria_id', '!=', 3)
            ->groupBy('year_id')
            ->orderBy('year_id', 'asc') // Opcional: para que los años estén en orden
            ->pluck('total', 'year_id') // Esto creará un array con el año como clave y el total como valor
            ->all(); // Convierte la colección en un array plano

        // Para el gráfico, Filament espera un array de valores (el total de investigaciones)
        // en el orden correcto. Los años se usarán como etiquetas en el frontend.
        $datosParaGrafico = array_values($investigacionesPorAno);


        /* dd($investigacionesPorAno); */

        return [
            Stat::make('Total Autores', $this->getAutores())
                ->description('220% de Incremento en el 2025')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Investigaciones', $this->getInvestigaciones())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($datosParaGrafico),
            Stat::make('Total Artículos', $this->getArticulos())
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }

    //Estadísticas Totales de Autores
    protected function getAutores()
    {
        return Autor::count();
    }

    //Estadísticas Totales de Investigaciones
    protected function getInvestigaciones()
    {
        $trabaAcad = Investigacion::where('categoria_id', '!=', 3)->get()->count();

        return $trabaAcad;
    }

    //Estadísticas Totales de Artículos
    protected function getArticulos()
    {
        $articulos = Investigacion::where('categoria_id', 3)->get()->count();

        return $articulos;
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class AutoresPublicaciones extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.pages.autores-publicaciones';

    protected static ?string $title = 'Trabajos Académicos por Autor';

    public $autores;

    public function mount()
    {
        $this->autores = DB::table('investigacion_autor as ia')
            ->join('investigaciones as i', 'ia.investigacion_id', '=', 'i.id')
            ->join('autors as a', 'ia.autor_id', '=', 'a.id')
            ->join('categorias as c', 'i.categoria_id', '=', 'c.id')
            ->where('ia.rol', '=', 'Autor')
            ->whereIn('i.estado_id', [2, 3])
            ->select(
                'a.id',
                'a.nombres',
                'a.imagen',
                'a.Estado',
                'c.name as categoria',
                DB::raw('COUNT(i.id) as total')
            )
            ->groupBy('a.id', 'a.nombres', 'a.Estado', 'a.imagen', 'c.name')
            ->orderBy('a.nombres', 'asc')
            ->orderBy('c.name', 'asc')
            ->get()
            ->groupBy('id'); // agrupamos por autor
    }
}

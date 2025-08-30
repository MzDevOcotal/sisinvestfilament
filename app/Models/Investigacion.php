<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Autor;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Investigacion extends Model
{
    use HasFactory;


    protected $table = 'investigaciones';

    protected $fillable = [
        'name',
        'avance',
        'area_id',
        'sede_id',
        'categoria_id',
        'year_id',
        'enfoque_id',
        'estado_id',
        'linea_id',
        'PDF',
        'link',

    ];

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'investigacion_autor', 'investigacion_id', 'autor_id')
            ->wherePivot('rol', 'Autor');
    }

    public function asesores()
    {
        return $this->belongsToMany(Autor::class, 'investigacion_autor', 'investigacion_id', 'autor_id')
            ->wherePivot('rol', 'Asesor');
    }

    public function linea()
    {
        return $this->belongsTo(Linea::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
    public function enfoque()
    {
        return $this->belongsTo(Enfoque::class);
    }
    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}

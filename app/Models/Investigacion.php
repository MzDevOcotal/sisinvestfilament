<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    ];
    

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

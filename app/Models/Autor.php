<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Investigacion;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Autor extends Model
{
    use HasFactory;
    protected $fillable = [
        'imagen',
        'tituloacadautor',
        'nombres',
        'apellidos',
        'cedula',
        'celular',
        'direccion',
        'correo',
        'orcid',
        'sede_id',
        'country_id',
        'state_id',
        'city_id',
    ];

    public function investigaciones(): BelongsToMany
    {
        return $this->belongsToMany(Investigacion::class, 'investigacion_autor', 'autor_id', 'investigacion_id')
            ->withPivot('rol')
            ->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
}

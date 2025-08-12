<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cine_id',

    ];  

    public function cine()
    {
        return $this->belongsTo(Cine::class);
    }
}

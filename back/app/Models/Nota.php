<?php

// app/Models/Nota.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asignacion_id',
        'estudiante_id',
        'ser','saber','hacer','decidir',
        'total',
        'observacion',
    ];

    protected static function booted()
    {
        static::saving(function (Nota $nota) {
            // clamp en rangos
            $nota->ser     = max(0, min(10, (int)$nota->ser));
            $nota->saber   = max(0, min(35,(int)$nota->saber));
            $nota->hacer   = max(0, min(35,(int)$nota->hacer));
            $nota->decidir = max(0, min(10, (int)$nota->decidir));

            $nota->total = $nota->ser + $nota->saber + $nota->hacer + $nota->decidir; // máx 90
        });
    }

    public function asignacion(){ return $this->belongsTo(Asignacion::class); }
    public function estudiante(){ return $this->belongsTo(Estudiante::class); }
}

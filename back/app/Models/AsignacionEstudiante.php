<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignacionEstudiante extends Model{
    use SoftDeletes;

    protected $fillable = [
        'asignacion_id',
        'estudiante_id',
    ];

    public function asignacion() {
        return $this->belongsTo(Asignacion::class);
    }

    public function estudiante() {
        return $this->belongsTo(Estudiante::class);
    }
}

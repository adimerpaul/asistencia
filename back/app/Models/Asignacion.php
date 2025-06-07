<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asignacion extends Model
{
    use SoftDeletes;

    protected $table = 'asignaciones';
    protected $fillable = [
        'user_id',
        'docente_id',
        'curso_id',
        'unidadEducativa',
        'taller',
        'fases',
        'docentesEncargados',
        'anioFormacion',
        'gestion',
    ];

    // Relaciones
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function curso() {
        return $this->belongsTo(Curso::class);
    }
    public function docente() {
        return $this->belongsTo(Docente::class);
    }
}

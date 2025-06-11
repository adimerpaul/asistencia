<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'asignacion_estudiantes')
            ->withPivot('id', 'deleted_at')
            ->withTimestamps()
            ->wherePivotNull('deleted_at'); // ✅ filtrar solo relaciones activas
    }
}

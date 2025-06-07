<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'descripcion',
        'formacion',
        'tipo',
        'icono',
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}

<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller{
    public function store(Request $request)
    {
        $data = $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'asistencia' => 'required|in:Presente,Ausente,Tarde',
            'fecha' => 'nullable|date',
        ]);

        $asistencia = Asistencia::updateOrCreate(
            [
                'asignacion_id' => $data['asignacion_id'],
                'estudiante_id' => $data['estudiante_id'],
                'fecha' => $data['fecha'] ?? now()->toDateTimeString(),
            ],
            [
                'asistencia' => $data['asistencia']
            ]
        );

        return response()->json($asistencia, 201);
    }
}

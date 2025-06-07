<?php

namespace App\Http\Controllers;

use App\Models\AsignacionEstudiante;
use Illuminate\Http\Request;

class AsignacionEstudianteController extends Controller
{
    public function index()
    {
        return AsignacionEstudiante::with(['asignacion', 'estudiante'])->orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asignacion_id' => 'required|exists:asignaciones,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
        ]);

        $asignado = AsignacionEstudiante::create($validated);
        return response()->json($asignado, 201);
    }

    public function destroy(AsignacionEstudiante $asignacionEstudiante)
    {
        $asignacionEstudiante->delete();
        return response()->json(['message' => 'Estudiante removido de la asignación']);
    }
}

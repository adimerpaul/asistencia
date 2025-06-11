<?php

namespace App\Http\Controllers;

use App\Models\AsignacionEstudiante;
use Illuminate\Http\Request;

class AsignacionEstudianteController extends Controller{
    public function destroyById($id){
        $registro = AsignacionEstudiante::find($id);

        if (!$registro) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Eliminado correctamente por ID']);
    }
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

        $exists = \App\Models\AsignacionEstudiante::where($validated)->first();
        if ($exists) {
            return response()->json(['message' => 'Ya está asignado'], 409);
        }

        $relacion = \App\Models\AsignacionEstudiante::create($validated);

        return response()->json($relacion, 201);
    }

    public function destroy($asignacion_id, $estudiante_id)
    {
        $registro = AsignacionEstudiante::where('asignacion_id', $asignacion_id)
            ->where('estudiante_id', $estudiante_id)
            ->first();

        if (!$registro) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Eliminado correctamente']);
    }
}

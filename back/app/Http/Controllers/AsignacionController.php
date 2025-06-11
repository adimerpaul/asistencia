<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index() {
        return Asignacion::with(['user','docente','curso','estudiantes'])->orderBy('id', 'desc')->get();
    }

    public function store(Request $request) {
        $user = $request->user();

        $validated = $request->validate([
            'curso_id' => 'required|integer',
            'docente_id' => 'required|integer',
            'unidadEducativa' => 'required|string',
            'taller' => 'nullable|string',
            'fases' => 'nullable|string',
            'docentesEncargados' => 'nullable|string',
            'anioFormacion' => 'required|string',
            'gestion' => 'required',
        ]);

        $validated['user_id'] = $user->id;
        error_log(json_encode($validated)); // Log the validated data for debugging
        $asignacion = Asignacion::create($validated);
        return response()->json($asignacion, 201);
    }

    public function update(Request $request, Asignacion $asignacion) {
        $user = $request->user();
        $validated = $request->validate([
//            'user_id' => 'required|integer',
            'curso_id' => 'required|integer',
            'unidadEducativa' => 'required|string',
            'taller' => 'nullable|string',
            'fases' => 'nullable|string',
            'docentesEncargados' => 'nullable|string',
            'anioFormacion' => 'required|string',
            'gestion' => 'required|string',
        ]);

        $validated['user_id'] = $user->id;
        $asignacion->update($validated);
        return response()->json($asignacion);
    }
    public function show($id,Request $request) {
        $user = $request->user();
        $docente = $user->docente_id;
        $asignacion = Asignacion::with(['curso', 'estudiantes'])->where('id', $id)
            ->where('docente_id', $docente)
            ->firstOrFail();
        if (!$asignacion) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }
        return response()->json($asignacion);
    }

    public function destroy(Asignacion $asignacion) {
        $asignacion->delete();
        return response()->json(['message' => 'Asignación eliminada']);
    }
}

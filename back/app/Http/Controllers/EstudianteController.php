<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    public function index()
    {
        return Estudiante::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'ci' => 'required|unique:estudiantes,ci',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string',
        ]);

        $estudiante = Estudiante::create($validated);
        return response()->json($estudiante, 201);
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'ci' => 'required|unique:estudiantes,ci,' . $estudiante->id,
            'email' => 'nullable|email',
            'telefono' => 'nullable|string',
        ]);

        $estudiante->update($validated);
        return response()->json($estudiante);
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return response()->json(['message' => 'Estudiante eliminado']);
    }
}

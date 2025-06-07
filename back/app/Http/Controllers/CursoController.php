<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        return Curso::orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable|string',
            'formacion' => 'required',
            'tipo' => 'required',
            'icono' => 'required',
        ]);

        $curso = Curso::create($validated);
        return response()->json($curso, 201);
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable|string',
            'formacion' => 'required',
            'tipo' => 'required',
            'icono' => 'required',
        ]);

        $curso->update($validated);
        return response()->json($curso);
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return response()->json(['message' => 'Curso eliminado']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Docente;

class DocenteController extends Controller
{
    public function index()
    {
        return Docente::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'ci' => 'required|unique:docentes,ci',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string',
        ]);

        $docente = Docente::create($validated);
        return response()->json($docente, 201);
    }

    public function update(Request $request, Docente $docente)
    {
        $validated = $request->validate([
            'nombre' => 'required',
            'ci' => 'required|unique:docentes,ci,' . $docente->id,
            'email' => 'nullable|email',
            'telefono' => 'nullable|string',
        ]);

        $docente->update($validated);
        return response()->json($docente);
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return response()->json(['message' => 'Docente eliminado']);
    }
}

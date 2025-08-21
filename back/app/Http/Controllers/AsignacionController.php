<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    public function reporte(Asignacion $asignacion)
    {
        $asignacion->load([
            'curso:id,nombre,descripcion,formacion,tipo',
            'docente:id,nombre',
            'estudiantes:id,nombre,ci'
        ]);

        $pdf = PDF::loadView('reportes.registro_evaluaciones', [
            'asignacion' => $asignacion
        ])->setPaper('a4', 'landscape');

        $filename = 'registro_evaluaciones_'.$asignacion->id.'.pdf';
        return $pdf->stream($filename); // o ->download($filename)
    }
    public function index(Request $request)
    {
        // Parámetros
        $perPage   = (int) $request->get('per_page', 0);   // 0 = sin paginar
        $gestion   = $request->get('gestion');
        $docenteId = $request->get('docente_id');
        $cursoId   = $request->get('curso_id');
        $paralelo  = $request->get('paralelo');            // <- si no existe columna, cambia a 'taller'
        $search    = $request->get('search');
        $withEst   = $request->boolean('with_estudiantes', false);

        // Query base con SELECT mínimo y WITH liviano
        $q = Asignacion::query()
            ->select([
                'id','user_id','docente_id','curso_id',
                'unidadEducativa','taller','fases','docentesEncargados',
                'anioFormacion','gestion',
                // 'paralelo', // <- descomenta si existe columna
            ])
            ->with([
                'user:id,name',
                'docente:id,nombre',
                'curso:id,nombre',
            ])
            ->orderByDesc('id');

        // Cargar estudiantes solo si lo piden (ahorra tiempo/ancho de banda)
        if ($withEst) {
            $q->with(['estudiantes:id,nombre']);
        }

        // Filtros exactos
        if (!empty($gestion))   $q->where('gestion', $gestion);
        if (!empty($docenteId)) $q->where('docente_id', $docenteId);
        if (!empty($cursoId))   $q->where('curso_id', $cursoId);

        // Filtro por "paralelo" (o 'taller' si corresponde)
        if (!empty($paralelo)) {
            // Si no tienes la columna 'paralelo', cambia a ->where('taller','like',...)
            $q->where('paralelo', 'like', "%{$paralelo}%");
        }

        // Búsqueda de texto
        if (!empty($search)) {
            $s = $search;
            $q->where(function ($sub) use ($s) {
                $sub->where('unidadEducativa', 'like', "%$s%")
                    ->orWhere('taller', 'like', "%$s%")
                    ->orWhere('fases', 'like', "%$s%")
                    ->orWhere('docentesEncargados', 'like', "%$s%")
                    ->orWhere('anioFormacion', 'like', "%$s%")
                    ->orWhereHas('curso', fn($c) => $c->where('nombre','like',"%$s%"))
                    ->orWhereHas('docente', fn($d) => $d->where('nombre','like',"%$s%"));
                // ->orWhere('paralelo', 'like', "%$s%"); // si tienes la columna
            });
        }

        // Respuesta (paginada o no)
        if ($perPage > 0) {
            return $q->paginate($perPage);
        }
        return $q->get();
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
    function asignacionesFind($id) {
        $asignaciones = Asignacion::with(['user','docente','curso','estudiantes'])->find($id);
        return response()->json($asignaciones);
    }
    public function show($id, Request $request)
    {
        $withEst = $request->boolean('with_estudiantes', false);

        $query = Asignacion::with(['curso', 'docente']);   // <- siempre curso y docente
        if ($withEst) {
            $query->with(['estudiantes']);                 // <- sólo si se solicita
        }

        $asignacion = $query->findOrFail($id);

        return response()->json($asignacion);
    }

    public function destroy(Asignacion $asignacion) {
        $asignacion->delete();
        return response()->json(['message' => 'Asignación eliminada']);
    }
}

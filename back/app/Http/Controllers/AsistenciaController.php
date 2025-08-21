<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Asignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AsistenciaController extends Controller
{
    // GET /asistencias?asignacion_id=..&fecha=YYYY-MM-DD
    public function index(Request $request)
    {
        $data = $request->validate([
            'asignacion_id' => ['required','exists:asignaciones,id'],
            'fecha'         => ['required','date'],
        ]);

        $rows = Asistencia::where('asignacion_id', $data['asignacion_id'])
            ->whereDate('fecha', Carbon::parse($data['fecha'])->toDateString())
            ->get(['estudiante_id','asistencia']);

        // devolver como mapa estudiante_id => asistencia
        return response()->json([
            'asignacion_id' => (int)$data['asignacion_id'],
            'fecha'         => Carbon::parse($data['fecha'])->toDateString(),
            'items'         => $rows->mapWithKeys(fn($r) => [$r->estudiante_id => $r->asistencia]),
        ]);
    }

    // POST /asistencias   (single o batch)
    public function store(Request $request)
    {
        $isBatch = is_array($request->input('items'));
        if ($isBatch) {
            return $this->storeBatch($request);
        }

        $data = $request->validate([
            'asignacion_id' => ['required','exists:asignaciones,id'],
            'estudiante_id' => ['required','exists:estudiantes,id'],
            'asistencia'    => ['required', Rule::in(['Presente','Ausente','Tarde','Licencia'])],
            'fecha'         => ['nullable','date'],
        ]);

        $fecha = Carbon::parse($data['fecha'] ?? now())->toDateString();

        // Evita duplicados en el día usando whereDate
        $row = Asistencia::updateOrCreate(
            [
                'asignacion_id' => $data['asignacion_id'],
                'estudiante_id' => $data['estudiante_id'],
                'fecha'         => $fecha,
            ],
            ['asistencia' => $data['asistencia']]
        );

        return response()->json($row->refresh(), 201);
    }

    // Batch: { asignacion_id, fecha, items: [{estudiante_id, asistencia}, ...] }
    protected function storeBatch(Request $request)
    {
        $data = $request->validate([
            'asignacion_id'       => ['required','exists:asignaciones,id'],
            'fecha'               => ['required','date'],
            'items'               => ['required','array','min:1'],
            'items.*.estudiante_id' => ['required','exists:estudiantes,id'],
            'items.*.asistencia'    => ['required', Rule::in(['Presente','Ausente','Tarde','Licencia'])],
        ]);

        $fecha = Carbon::parse($data['fecha'])->toDateString();

        DB::transaction(function () use ($data, $fecha) {
            foreach ($data['items'] as $it) {
                Asistencia::updateOrCreate(
                    [
                        'asignacion_id' => $data['asignacion_id'],
                        'estudiante_id' => $it['estudiante_id'],
                        'fecha'         => $fecha,
                    ],
                    ['asistencia' => $it['asistencia']]
                );
            }
        });

        return response()->json(['message' => 'Asistencias guardadas'], 201);
    }
}

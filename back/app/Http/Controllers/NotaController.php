<?php

// app/Http/Controllers/NotaController.php
namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotaController extends Controller
{
    // GET /notas?asignacion_id=XX  -> mapa estudiante_id => notas
    public function index(Request $request)
    {
        $data = $request->validate([
            'asignacion_id' => ['required','exists:asignaciones,id'],
        ]);

        $rows = Nota::where('asignacion_id', $data['asignacion_id'])->get();

        return response()->json([
            'items' => $rows->mapWithKeys(function ($n) {
                return [
                    $n->estudiante_id => [
                        'ser'     => (int)$n->ser,
                        'saber'   => (int)$n->saber,
                        'hacer'   => (int)$n->hacer,
                        'decidir' => (int)$n->decidir,
                        'total'   => (int)$n->total,
                        'observacion' => $n->observacion,
                    ]
                ];
            })
        ]);
    }

    // POST /notas  (single o batch)
    // single: { asignacion_id, estudiante_id, ser, saber, hacer, decidir, observacion? }
    // batch:  { asignacion_id, items: [{estudiante_id, ser, saber, hacer, decidir, observacion?}, ...] }
    public function store(Request $request)
    {
        $isBatch = is_array($request->input('items'));

        if ($isBatch) {
            $data = $request->validate([
                'asignacion_id'                 => ['required','exists:asignaciones,id'],
                'items'                         => ['required','array','min:1'],
                'items.*.estudiante_id'         => ['required','exists:estudiantes,id'],
                'items.*.ser'                   => ['required','integer','min:0','max:10'],
                'items.*.saber'                 => ['required','integer','min:0','max:35'],
                'items.*.hacer'                 => ['required','integer','min:0','max:35'],
                'items.*.decidir'               => ['required','integer','min:0','max:10'],
                'items.*.observacion'           => ['nullable','string','max:255'],
            ]);

            foreach ($data['items'] as $it) {
                Nota::updateOrCreate(
                    [
                        'asignacion_id' => $data['asignacion_id'],
                        'estudiante_id' => $it['estudiante_id'],
                    ],
                    [
                        'ser'     => $it['ser'],
                        'saber'   => $it['saber'],
                        'hacer'   => $it['hacer'],
                        'decidir' => $it['decidir'],
                        'observacion' => $it['observacion'] ?? null,
                    ]
                );
            }

            return response()->json(['message' => 'Notas guardadas'], 201);
        }

        // single
        $data = $request->validate([
            'asignacion_id' => ['required','exists:asignaciones,id'],
            'estudiante_id' => ['required','exists:estudiantes,id'],
            'ser'     => ['required','integer','min:0','max:10'],
            'saber'   => ['required','integer','min:0','max:35'],
            'hacer'   => ['required','integer','min:0','max:35'],
            'decidir' => ['required','integer','min:0','max:10'],
            'observacion' => ['nullable','string','max:255'],
        ]);

        $row = Nota::updateOrCreate(
            [
                'asignacion_id' => $data['asignacion_id'],
                'estudiante_id' => $data['estudiante_id'],
            ],
            [
                'ser'     => $data['ser'],
                'saber'   => $data['saber'],
                'hacer'   => $data['hacer'],
                'decidir' => $data['decidir'],
                'observacion' => $data['observacion'] ?? null,
            ]
        );

        return response()->json($row->refresh(), 201);
    }
}

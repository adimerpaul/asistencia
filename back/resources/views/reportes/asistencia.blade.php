@php
    $curso = $asignacion->curso;
    $doc   = $asignacion->docente;
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>
    <style>
        @page{ margin:8mm 8mm; size:A4 landscape; }
        body{ font-family: DejaVu Sans, sans-serif; font-size:10px; color:#111; }
        .title{ text-align:center; font-weight:700; font-size:13px; margin-bottom:6px; }
        table{ width:100%; border-collapse:collapse; table-layout:fixed; }
        th,td{ border:0.6px solid #000; padding:2px 3px; vertical-align:middle; }
        .small{ font-size:9px; line-height:1.15; }
        .thead-lite th{ background:#efefef; font-weight:600; text-transform:uppercase; }
        .grid-head th{ background:#f1f1f1; font-weight:700; font-size:9px; }
        .center{ text-align:center; }
        .right{ text-align:right; }
        .nowrap{ white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        tbody tr:nth-child(even){ background:#f8f8f8; }
        .muted{ color:#444; }
        .chip{ display:inline-block; border:0.6px solid #000; padding:1px 4px; border-radius:3px; font-size:9px; }
    </style>
</head>
<body>
<div class="title">REPORTE DE ASISTENCIA</div>

<table class="small">
    <tr class="thead-lite">
        <th>ESFM/UAI</th>
        <th>Gestión</th>
        <th>Año de Formación</th>
        <th>Unidad de Formación</th>
        <th>Docente</th>
        <th class="center">Rango</th>
    </tr>
    <tr>
        <td class="nowrap">{{ env('APP_NAME','Unidad Académica') }}</td>
        <td class="center">{{ $asignacion->gestion }}</td>
        <td class="center">{{ $asignacion->anioFormacion }}</td>
        <td class="nowrap">{{ $curso->nombre }}</td>
        <td class="nowrap">{{ $doc?->nombre }}</td>
        <td class="center">
            <span class="chip">Desde: {{ $desde ?? '—' }}</span>
            &nbsp;<span class="chip">Hasta: {{ $hasta ?? '—' }}</span>
        </td>
    </tr>
    <tr>
        <th class="muted">Unidad Educativa</th>
        <td class="nowrap" colspan="5">{{ $asignacion->unidadEducativa }}</td>
    </tr>
</table>

<br>

<table>
    <colgroup>
        <col style="width:3.5%">
        <col style="width:36%">
        <col style="width:12%">
        <col style="width:8%">
        <col style="width:8%">
        <col style="width:8%">
        <col style="width:8%">
        <col style="width:8%">
    </colgroup>
    <thead class="grid-head">
    <tr>
        <th class="center">#</th>
        <th>Apellidos y Nombres</th>
        <th class="center">CI</th>
        <th class="center">Presentes</th>
        <th class="center">Tardes</th>
        <th class="center">Ausentes</th>
        <th class="center">Licencias</th>
        <th class="center">% Asistencia</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($asignacion->estudiantes as $i => $est)
        @php
            $r = $resumen[$est->id] ?? null;
            $pres = (int)($r->presentes ?? 0);
            $tard = (int)($r->tardes ?? 0);
            $aus  = (int)($r->ausentes ?? 0);
            $lic  = (int)($r->licencias ?? 0);
            $pct  = (int)($r->porcentaje ?? 0);
        @endphp
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td class="nowrap">{{ $est->nombre }}</td>
            <td class="center nowrap">{{ $est->ci }}</td>
            <td class="center">{{ $pres }}</td>
            <td class="center">{{ $tard }}</td>
            <td class="center">{{ $aus }}</td>
            <td class="center">{{ $lic }}</td>
            <td class="center">{{ $pct }}%</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

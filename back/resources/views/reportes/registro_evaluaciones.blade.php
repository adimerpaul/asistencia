@php
    $curso = $asignacion->curso;
    $doc   = $asignacion->docente;
    $colsEvaluaciones = 12;   // cantidad de columnas vacías para evaluaciones
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro de Evaluaciones</title>
    <style>
        /* Página y tipografía */
        @page { margin: 8mm 8mm; size: A4 landscape; } /* más compacto */
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color:#111; }

        /* Título */
        .title {
            text-align:center; font-weight:700; font-size:13px;
            margin-bottom:6px; letter-spacing: .3px;
        }

        /* Tablas base */
        table { width:100%; border-collapse:collapse; table-layout:fixed; }
        th, td { border:0.6px solid #000; padding:2px 3px; vertical-align:middle; }
        .no-border td, .no-border th { border:none; }

        /* Cabecera de datos (más compacta) */
        .small { font-size:9px; line-height:1.15; }
        .thead-lite th {
            background:#efefef; font-weight:600; text-transform:uppercase;
            letter-spacing:.2px;
        }

        /* Tabla de estudiantes */
        .center { text-align:center; }
        .right { text-align:right; }
        .nowrap { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Fila cebra para lectura más amable */
        tbody tr:nth-child(even) { background:#f8f8f8; }

        /* Encabezados de la grilla */
        .grid-head th {
            background:#f1f1f1; font-weight:700; font-size:9px;
        }

        /* Cols proporcionales (ajustadas a lo compacto) */
        .col-num { width:3.5%; }
        .col-nom { width:32%; }
        .col-ci  { width:10%; }
        .col-fin { width:6%; }

        /* Columnas de evaluaciones (iguales y estrechas) */
        .eval-col { width: (100% - (3.5% + 32% + 10% + 6%)); } /* comentario informativo */
        /* Para PDF, definimos por colgroup más abajo. */

        /* Micro-ajustes visuales */
        .muted { color:#444; }
        .chip { display:inline-block; border:0.6px solid #000; padding:1px 4px; border-radius:3px; font-size:9px; }

        /* Evitar cortes feos */
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
<div class="title">REGISTRO DE EVALUACIONES</div>

<!-- Cabecera -->
<table class="small">
    <tr class="thead-lite">
        <th>ESFM/UAI</th>
        <th>Gestión</th>
        <th>Año de Formación</th>
        <th>Unidad de Formación</th>
        <th>Docente</th>
        <th class="center">Info</th>
    </tr>
    <tr>
        <td class="nowrap">{{ env('APP_NAME', 'Unidad Académica') }}</td>
        <td class="center">{{ $asignacion->gestion }}</td>
        <td class="center">{{ $asignacion->anioFormacion }}</td>
        <td class="nowrap">{{ $curso->nombre }}</td>
        <td class="nowrap">{{ $doc?->nombre }}</td>
        <td class="center">
            <span class="chip">{{ $curso->tipo }}</span>
            &nbsp;
            <span class="chip">{{ $curso->formacion }}</span>
        </td>
    </tr>
    <tr>
        <th class="muted">Unidad Educativa</th>
        <td class="nowrap" colspan="5">{{ $asignacion->unidadEducativa }}</td>
    </tr>
</table>

<br>

<!-- Tabla estudiantes -->
<table>
    <!-- Colgroup para compactar anchos (PDF-friendly) -->
    <colgroup>
        <col style="width:3.5%">
        <col style="width:32%">
        <col style="width:10%">
        @for ($i=1; $i<=$colsEvaluaciones; $i++)
            <col style="width:3.7%">
        @endfor
        <col style="width:6%">
    </colgroup>

    <thead class="grid-head">
    <tr>
        <th class="center col-num">#</th>
        <th class="col-nom">Apellidos y Nombres</th>
        <th class="center col-ci">CI</th>
        @for ($i=1; $i<=$colsEvaluaciones; $i++)
            <th class="center">E{{ $i }}</th>
        @endfor
        <th class="center col-fin">Final</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($asignacion->estudiantes as $i => $est)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="nowrap">{{ $est->nombre }}</td>
            <td class="center nowrap">{{ $est->ci }}</td>
            @for ($j=1; $j<=$colsEvaluaciones; $j++)
                <td class="center">&nbsp;</td>
            @endfor
            <td class="center">&nbsp;</td>
        </tr>
    @endforeach

    @for ($k = count($asignacion->estudiantes); $k < 30; $k++)
        <tr>
            <td class="center">{{ $k + 1 }}</td>
            <td>&nbsp;</td>
            <td class="center">&nbsp;</td>
            @for ($j=1; $j<=$colsEvaluaciones; $j++)
                <td>&nbsp;</td>
            @endfor
            <td>&nbsp;</td>
        </tr>
    @endfor
    </tbody>
</table>

<br>

<!-- Firmas -->
<table class="no-border small" style="margin-top:6px;">
    <tr>
        <td class="center">_________________________________<br>Firma Docente</td>
        <td class="center">_________________________________<br>Dirección Académica</td>
        <td class="center">_________________________________<br>Sello</td>
    </tr>
</table>
</body>
</html>

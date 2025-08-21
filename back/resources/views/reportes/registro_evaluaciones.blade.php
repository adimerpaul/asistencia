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
        @page { margin: 18mm 12mm; size: A4 landscape; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .title { text-align:center; font-weight:bold; font-size:16px; margin-bottom:8px; }
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #000; padding:3px 4px; }
        .no-border td, .no-border th { border:none; }
        .small { font-size: 10px; }
        .center { text-align: center; }
        .w-20 { width:20%; } .w-15 { width:15%; } .w-10 { width:10%; } .w-5 { width:5%; }
    </style>
</head>
<body>
<div class="title">REGISTRO DE EVALUACIONES</div>

<!-- Cabecera -->
<table class="small">
    <tr>
        <th class="w-20">ESFM/UAI</th>
        <td>{{ env('APP_NAME', 'Unidad Académica') }}</td>
        <th class="w-10">Gestión</th>
        <td class="w-10 center">{{ $asignacion->gestion }}</td>
        <th class="w-10">Año de Formación</th>
        <td class="w-10 center">{{ $asignacion->anioFormacion }}</td>
    </tr>
    <tr>
        <th>Unidad de Formación</th>
        <td>{{ $curso->nombre }}</td>
        <th>Docente</th>
        <td colspan="3">{{ $doc?->nombre }}</td>
    </tr>
    <tr>
        <th>Unidad Educativa</th>
        <td>{{ $asignacion->unidadEducativa }}</td>
        <th>Especialidad</th>
        <td>{{ $curso->tipo }}</td>
        <th>Formación</th>
        <td>{{ $curso->formacion }}</td>
    </tr>
</table>

<br>

<!-- Tabla estudiantes -->
<table>
    <thead>
    <tr>
        <th class="w-5 center">#</th>
        <th>Apellidos y Nombres</th>
        <th class="w-10 center">CI</th>
        @for ($i=1; $i<=$colsEvaluaciones; $i++)
            <th class="center"> </th>
        @endfor
        <th class="w-10 center">Final</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($asignacion->estudiantes as $i => $est)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $est->nombre }}</td>
            <td class="center">{{ $est->ci }}</td>
            @for ($j=1; $j<=$colsEvaluaciones; $j++)
                <td>&nbsp;</td>
            @endfor
            <td>&nbsp;</td>
        </tr>
    @endforeach
    @for ($k = count($asignacion->estudiantes); $k < 30; $k++)
        <tr>
            <td class="center">{{ $k + 1 }}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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
<table class="no-border small" style="margin-top:10px;">
    <tr>
        <td class="center">_________________________________<br>Firma Docente</td>
        <td class="center">_________________________________<br>Dirección Académica</td>
        <td class="center">_________________________________<br>Sello</td>
    </tr>
</table>
</body>
</html>

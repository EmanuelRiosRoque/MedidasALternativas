<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0px; }
        body {
            margin: 0px;
            font-family: DejaVu Sans, sans-serif;
            position: relative;
        }
        .fondo {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .campo {
            position: absolute;
            color: #000;
            font-size: 16px;
            z-index: 1;
        }
        .dia    { top: 126px; left: 375px; }
        .mes    { top: 126px; left: 520px; }
        .anio   { top: 126px; left: 700px; }
    </style>
</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/amparo_representante.jpeg') }}" alt="Fondo">

    <p class="campo dia">{{ $dia }}</p>
    <p class="campo mes">{{ $mes }}</p>
    <p class="campo anio">{{ $ultimoDigitoAnio }}</p>
</body>
</html>

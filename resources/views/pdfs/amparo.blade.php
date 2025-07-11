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
        .dia    { top: 122px; left: 332px; }
        .mes    { top: 122px; left: 480px; }
        .anio   { top: 122px; left: 630px; }
    </style>
</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/amparo.jpeg') }}" alt="Fondo">

    <p class="campo dia">{{ $dia }}</p>
    <p class="campo mes">{{ $mes }}</p>
    <p class="campo anio">{{ $ultimoDigitoAnio }}</p>
</body>
</html>

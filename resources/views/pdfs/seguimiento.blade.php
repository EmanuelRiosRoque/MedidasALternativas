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
        .fecha    { top: 390px; left: 300px;}
        .firma1   { top: 400px; left: 300px;}
    </style>
</head>
<body>
    {{-- Página 1 --}}
    <div style="page-break-after: always; position: relative; width: 100%; height: 100%;">
        <img class="fondo" src="{{ public_path('img/documentos/seguimiento_p1.jpg') }}" alt="Fondo">
        <p class="campo fecha">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d \d\e F \d\e Y') }}</p>
        {{-- … más campos página 1 --}}
    </div>

    {{-- Página 2 --}}
    <div style="position: relative; width: 100%; height: 100%;">
        <img class="fondo" src="{{ public_path('img/documentos/seguimiento_p2.jpg') }}" alt="Fondo">
        <p class="campo firma1">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('d \d\e F \d\e Y') }}</p>
        {{-- … más campos página 2 --}}
    </div>
</body>

</html>

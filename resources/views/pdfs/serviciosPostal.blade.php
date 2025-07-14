<!DOCTYPE html>
<html>
<head>
    <style>
    @page {
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, sans-serif;
        position: relative;
    }

    .fondo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: auto;
        z-index: 0;
    }

    .campo {
        position: absolute;
        color: #000;
        font-size: 16px;
        z-index: 1;
    }

    /* Ajusta estos valores con prueba y error */
    .dia  { top: 110px; left: 330px; }
    .mes  { top: 110px; left: 470px; }
    .anio { top: 110px; left: 610px; }
</style>

</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/servicios_postal.jpeg') }}" alt="Fondo">
{{-- 
    <p class="campo dia">{{ $dia }}</p>
    <p class="campo mes">{{ $mes }}</p>
    <p class="campo anio">{{ $ultimoDigitoAnio }}</p> --}}
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0;
            size: letter portrait;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        .bloque {
            position: relative;
            width: 612pt;
            height: 264pt; /* 3 por hoja */
            page-break-inside: avoid;
        }

        .fondo {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 520pt;  /* reducido proporcionalmente */
            height: auto;
            z-index: 0;
        }

        .campo {
            position: absolute;
            color: #000;
            font-size: 12px;
            z-index: 1;
        }

        /* Reubicación proporcional */
        .nombre     { top: 60pt; left: 280pt; width: 350px }
        .calle      { top: 78pt; left: 290pt; }
        .numero     { top: 78pt; left: 490pt; }
        .colonia    { top: 100pt; left: 290pt; }
        .poblacion  { top: 120pt; left: 295pt; }
        .cp         { top: 134pt; left: 480pt; }
    </style>
</head>
<body>

@foreach ($documentos as $doc)
    <div class="bloque">
        <img class="fondo" src="{{ public_path('img/documentos/servicios_postal.jpeg') }}" alt="Fondo">

        <p class="campo nombre" 
            style="top: {{ strlen($doc['nombre']) > 30 ? '48pt' : '60pt' }};">
            {{ $doc['nombre'] }}
        </p>

        <p class="campo calle">{{ $doc['calle'] }}</p>
        {{-- <p class="campo numero">{{ $doc['numero'] }}</p> --}}
        <p class="campo colonia">{{ $doc['colonia'] }}</p>
        <p class="campo poblacion">{{ $doc['poblacion'] }}</p>
        <p class="campo cp">{{ $doc['cp'] }}</p>
    </div>
@endforeach

</body>
</html>

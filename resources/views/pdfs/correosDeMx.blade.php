<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0px;
            size: letter landscape; /* Hoja carta en horizontal */
        }

        body {
            margin: 0px;
            font-family: DejaVu Sans, sans-serif;
        }

        .bloque {
            position: relative;
            width: 792pt;     /* Ancho de carta horizontal */
            height: 306pt;    /* Altura de medio documento */
            page-break-inside: avoid;
        }

        .fondo {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 0;
            /* No se definen width ni height para respetar tamaño natural */
        }

        .campo {
            position: absolute;
            color: #000;
            font-size: 7px;
            z-index: 1;
        }

        .nombre {
            width: 216px;
        }
    </style>
</head>
<body>

@foreach ($documentos as $doc)
    <div class="bloque">
        <img class="fondo" src="{{ public_path('img/documentos/imagen2.jpg') }}" alt="Fondo">

        <p class="campo nombre" style="top: 151pt; left: 199pt;">{{ $doc['nombre'] }}</p>
        <p class="campo" style="top: 172pt; left: 199pt;">{{ $doc['domicilio'] }}</p>
        <p class="campo" style="top: 186pt; left: 199pt;">{{ $doc['lugar'] }}</p>
        <p class="campo" style="top: 200pt; left: 199pt;">{{ $doc['materia'] }}</p>
        <p class="campo" style="top: 200pt; left: 322pt;">{{ $doc['cp'] }}</p>
    </div>
@endforeach

</body>
</html>

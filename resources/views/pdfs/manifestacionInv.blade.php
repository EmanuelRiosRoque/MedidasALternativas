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
        .nombre1  { top: 122px; left: 75px; }
        .nombre2  { top: 690px; left: 290px; }
        .nombre3  { top: 305px; left: 75px; }
       
        .campo.nombre2 {
            width: 30%;         
            text-align: center;    
        }

        .campo.nombre3 {
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/manifestacionInv.jpg') }}" alt="Fondo">


    <p class="campo nombre1">{{ $nombreInvitado  }}</p>
    <p class="campo nombre3">{{ $nombreSolicitante }}</p>
    <p class="campo nombre2">C. {{ $nombreInvitado }}</p>
</body>
</html>

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
        .nombre_invitado { top: 195px; left: 76px; font-weight: bold;}
        .nombre_solicitante { top: 275px; left: 76px; font-size: 12px}
        .nombre_invitado_p2 { top: 158px; left: 76px; font-weight: bold;}
        .nombre_solicitante_p2 { top: 282px; left: 76px; font-size: 12px}
        .email_solicitante { top: 182px; left: 118px; font-size: 12px}
        .num_solicitante { top: 197px; left: 175px; font-size: 12px}
    </style>
</head>
<body>
    {{-- Página 1 --}}
    <div style="page-break-after: always; position: relative; width: 100%; height: 100%;">
        <img class="fondo" src="{{ public_path('img/documentos/invitacion_dos_p1.jpg') }}" alt="Fondo">
        <p class="campo nombre_invitado">{{ $nombre_solicitante }}</p>
        <p class="campo nombre_solicitante">{{ $nombre_invitado }}</p>

        {{-- … más campos página 1 --}}
    </div>

    {{-- Página 2 --}}
    <div style="position: relative; width: 100%; height: 100%;">
        <img class="fondo" src="{{ public_path('img/documentos/invitacion_dos_p2.jpg') }}" alt="Fondo">
        <p class="campo nombre_invitado_p2">{{ $nombre_solicitante }}</p>
        <p class="campo email_solicitante">{{ $email_solicitante }}</p>
        <p class="campo num_solicitante">{{ $num_solicitante }}</p>
        <p class="campo nombre_solicitante_p2">{{ $nombre_invitado }}</p>

        {{-- … más campos página 2 --}}
    </div>
</body>

</html>

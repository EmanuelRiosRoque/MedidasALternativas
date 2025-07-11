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
            width: 600pt;
            height: 249pt;
            display: block;
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
    <img class="fondo" src="{{ public_path('img/documentos/correos_de_mexico.jpeg') }}" alt="Fondo">
</body>
</html>

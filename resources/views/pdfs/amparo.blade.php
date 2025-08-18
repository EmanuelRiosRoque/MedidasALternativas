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

        .juzgado   { top: 158px; left: 325px; }

        .cdm   { top: 333px; left: 230px; }
        .juzgado2   { top: 577px; left: 305px; font-size: 12px; font-weight: bold}
        .x   { top: 482px; left: 622px; font-weight: bold }
        .domicilio   { top: 595px; left: 142px; font-size: 12px; }
        .cantidad   { top: 650px; left: 100px; font-weight: bold;}
        .gramos   { top: 650px; left: 575px; font-weight: bold;}
        .firma1   { top: 1010px; left: 100px; font-size: 12px }
        .firma2   { top: 1010px; left: 520px; font-size: 12px}
    </style>
</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/amparo.jpeg') }}" alt="Fondo">

    <p class="campo dia">{{ $dia }}</p>
    <p class="campo mes">{{ $mes }}</p>
    <p class="campo anio">{{ $ultimoDigitoAnio }}</p>
    <p class="campo juzgado">CENTRO DE JUSTICIA ALTERNATIVA</p>
    <p class="campo cdm">C O N D E S A</p>
    <p class="campo x">X</p>
    <p class="campo juzgado2">CENTRO DE JUSTICIA ALTERNATIVA</p>
    <p class="campo domicilio">NIÑOS HEROES 133, COL.DOCTORES, ALCALDIA CUAUHTEMOC,<br> C.P. 06720, CIUDAD DE MÉXICO</p>
    <p class="campo cantidad">1</p>
    <p class="campo gramos">0.20 grs</p>

    <p class="campo firma1">MTRA.ANA MARIA HERNANDEZ SANCHEZ</p>
    <p class="campo firma2">LIC.CAROLINA AVILA CALDERÓN</p>
</body>
</html>

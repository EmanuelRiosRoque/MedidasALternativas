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
        .juzgado   { top: 170px; left: 225px; }
        .cdm   { top: 333px; left: 270px; }
        .x   { top: 487px; left: 610px; font-weight: bold }


        .cargo   { top: 612px; left: 143px; font-size: 12px; text-align: center }
        .firma1   { top: 700px; left: 100px; font-size: 12px }
        .firma2   { top: 700px; left: 500px; font-size: 12px}

        .cantidad   { top: 815px; left: 250px; font-weight: bold; font-size: 12px}
        .gramos   { top: 815px; left: 648px; font-weight: bold; font-size: 12px}
    </style>
</head>
<body>
    <img class="fondo" src="{{ public_path('img/documentos/amparo_representante.jpeg') }}" alt="Fondo">

    <p class="campo dia">{{ $dia }}</p>
    <p class="campo mes">{{ $mes }}</p>
    <p class="campo anio">{{ $ultimoDigitoAnio }}</p>

    <p class="campo juzgado">CENTRO DE JUSTICIA ALTERNATIVA</p>
    <p class="campo cdm">C O N D E S A</p>
    <p class="campo x">X</p>

    <p class="campo cargo">DIRECTIRA DE MEDIACIÓN CIVIL- <br>MERCANTIL</p>
    <p class="campo firma1">MTRA.ANA MARIA HERNANDEZ SANCHEZ</p>
    <p class="campo firma2">LIC.CAROLINA AVILA CALDERÓN</p>

    <p class="campo cantidad">1 (UNO)</p>
    <p class="campo gramos">0.20 gr</p>



</body>
</html>

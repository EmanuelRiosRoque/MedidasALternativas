<!DOCTYPE html>
<html>
<head>
  <style>
    /* Hoja carta horizontal */
    @page { size: letter landscape; margin: 0; }
    html, body { margin:0; padding:0; font-family: DejaVu Sans, sans-serif; }

    .page { position:relative; width:792pt; height:612pt; overflow:hidden; }

    /* Medidas reales del sobre SEPOMEX: 1536×672 pt */
    :root { --scale: 0.515625; --offsetY: 132.75pt; }

    .design {
      position:absolute;
      width:1536pt; height:672pt;
      left:0; top:var(--offsetY);
      transform: scale(var(--scale));
      transform-origin: top left;
    }

    .bg { position:absolute; inset:0; background-size:100% 100%; }
    .bg.p1 { background-image:url('{{ public_path('img/documentos/sobres_sepomex_p1.jpg') }}'); border:4px dashed #000;}
    .bg.p2 { background-image:url('{{ public_path('img/documentos/sobres_sepomex_p2.jpg') }}'); border:4px dashed #000;}

    /* Campos con coordenadas originales del sobre */
    .campo { position:absolute; color:#000; z-index:1; font-size:30px; }
    .nombre    { top:315px; left:548px; font-weight:bold; font-size:26px; width:900px; text-align:left; }
    .calle     { top:348px; left:654px; width:800px; }
    .colonia   { top:383px; left:620px; width:800px; }
    .municipio { top:419px; left:700px; width:800px; }
  </style>
</head>
<body>
  @foreach ($documentos as $doc)
    {{-- Página 1: Frente --}}
    <div class="page" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
      <div class="design">
        <div class="bg p1"></div>

        <p class="campo nombre">{{ $doc['nombre'] }}</p>
        <p class="campo calle">{{ $doc['calle'] }}</p>
        <p class="campo colonia">{{ $doc['col'] }}</p>
        <p class="campo municipio">. {{ $doc['municipio'] }}</p>
      </div>
    </div>

    {{-- Página 2: Reverso --}}
    <div class="page" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
      <div class="design">
        <div class="bg p2"></div>
      </div>
    </div>
  @endforeach
</body>
</html>

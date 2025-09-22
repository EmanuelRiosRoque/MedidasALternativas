<style>
  @page { size: letter landscape; margin: 0; }
  html, body { margin:0; padding:0; font-family: DejaVu Sans, sans-serif; }

  .page { position:relative; width:792pt; height:612pt; overflow:hidden; }

  :root { --scale: 0.515625; --offsetY: 132.75pt; }

  .design {
    position:absolute;
    width:1536pt; height:672pt;
    left:0; top:var(--offsetY);
    transform: scale(var(--scale));
    transform-origin: top left;
  }

  .bg { position:absolute; inset:0; background-size:100% 100%; }

  .bg.p1 { background-image:url('{{ public_path('img/documentos/sobre_personal_p1.jpg') }}'); border:4px dashed #000;}
  .bg.p2 { background-image:url('{{ public_path('img/documentos/sobre_personal_p2.jpg') }}'); border:4px dashed #000;}

  /* Solo el nombre, siempre centrado */
  .nombre {
    position:absolute;
    top:350px;       /* ajusta según tu fondo */
    left:0;
    width:103%;      /* ocupa todo el ancho */
    text-align:center;
    font-weight:bold;
    font-size:28px;  /* tamaño base */
}

</style>

<body>
@foreach ($documentos as $doc)
  {{-- Frente --}}
  <div class="page" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
    <div class="design">
      <div class="bg p1"></div>
      <p class="nombre">{{ $doc['nombre'] }}</p>
    </div>
  </div>

  {{-- Reverso --}}
  <div class="page" style="{{ !$loop->last ? 'page-break-after: always;' : '' }}">
    <div class="design">
      <div class="bg p2"></div>
    </div>
  </div>
@endforeach
</body>

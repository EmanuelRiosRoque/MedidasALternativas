<div x-data="{ tab: 'solicitud' }" class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
  
  {{-- ============ @PHP's para creacion de rutas, generadoras de deocumentos ============ --}}
  @php
      $docs = [
        'personal' => [
          ['label' => 'Invitación para invitación 1', 'hint' => 'PDF listo para imprimir', 'url' => route('invitacionUno.download', [$solicitud->id])],
          ['label' => 'Entrega personal invitación 2', 'hint' => 'Acta de entrega',        'url' => route('invitacionDos.download', [$solicitud->id])],
          ['label' => 'Sobre personal',                'hint' => 'Carátula para sobre',     'url' => route('sobrePersonal.download', [$solicitud->id])],
        ],
        'sepomex' => [
          ['label' => 'Amparo',               'hint' => 'Plantilla para envío',   'url' => route('descargar-amparo')],
          ['label' => 'Amparo Representante', 'hint' => 'Plantilla para envío',   'url' => route('descargar-amparoRepre')],
          ['label' => 'Correos México',       'hint' => 'Formato operativo',      'url' => route('descargar-correoMexico', [$solicitud->id])],
          ['label' => 'Servicio Postal',      'hint' => 'Instrucciones de envío', 'url' => route('descargar-servicioPostal', [$solicitud->id])],
          ['label' => 'Sobre SEPOMEX',        'hint' => 'Carátula para sobre',    'url' => route('sobreSepomex.download', [$solicitud->id])],
        ],
      ];
  @endphp  

  {{-- ============ TABS Superiores ============ --}}
  @include('components.solicitud.tabs')

  {{-- ============ TABS 1 ============ --}}
  <section x-show="tab==='solicitud'" x-cloak x-transition.opacity.duration.100ms class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
    <livewire:solicitud.asignar-facilitador :solicitud="$solicitud" />
  </section>

  {{-- ============ TABS 2 ============ --}}
  <section x-show="tab==='invitaciones'" x-cloak x-transition.opacity.duration.100ms class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
    <livewire:solicitud.invitaciones-panel :solicitud="$solicitud" />
  </section>

  <section x-show="tab==='observaciones'" x-cloak x-transition.opacity.duration.100ms class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
    <livewire:solicitud.observaciones :solicitud="$solicitud"/>
  </section>

  <section x-show="tab==='propuesta'" x-cloak x-transition.opacity.duration.100ms class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
    <livewire:solicitud.propuesta-mediacion :solicitud="$solicitud"/>
  </section>


  {{-- ============ MODALES ============ --}}
  @include('components.solicitud.modales')
</div>

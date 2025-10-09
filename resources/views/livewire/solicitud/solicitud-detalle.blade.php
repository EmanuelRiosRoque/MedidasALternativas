<div x-data="{ tab: 'solicitud' }"
     class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">

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

  <!-- Glow decorativo -->
  <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft"></div>

  <!-- Header: Tabs izquierda / Documentos derecha -->
  <div class="relative z-10 w-full max-w-8xl mb-4">
    <div class="flex items-center justify-between gap-3">

      <!-- Tabs -->
      <div class="inline-flex rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white/70 dark:bg-neutral-900/70 backdrop-blur p-1">
        <button
          type="button"
          class="px-4 sm:px-6 py-2 rounded-xl text-sm font-semibold transition"
          :class="tab==='solicitud'
            ? 'bg-emerald-600 text-white shadow'
            : 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800'"
          @click="tab='solicitud'">
          Detalle solicitud
        </button>
        <button
          type="button"
          class="px-4 sm:px-6 py-2 rounded-xl text-sm font-semibold transition"
          :class="tab==='invitaciones'
            ? 'bg-emerald-600 text-white shadow'
            : 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800'"
          @click="tab='invitaciones'">
          Invitaciones / Sesiones
        </button>
      </div>

      <!-- Botón Documentos (arriba derecha) -->
      @if ($solicitud->modalidad == 1)
        <flux:modal.trigger name="documentos">
          <flux:button variant="primary" icon="arrow-down-tray">Documentos</flux:button>
        </flux:modal.trigger>
      @endif
    </div>
  </div>

  {{-- Modal de Documentos (teleport automático) --}}
  @if ($solicitud->modalidad == 1)
    <flux:modal name="documentos" class="w-full max-w-5xl sm:max-w-6xl">
      <div x-data="{ medio_envio:'sepomex', docs:@js($docs) }" class="space-y-6 max-h-[75vh] overflow-y-auto px-1">
        <flux:heading size="lg">Documentos de envío</flux:heading>
        <flux:text class="mt-1">Selecciona el medio y descarga los formatos.</flux:text>

        <flux:radio.group x-model="medio_envio">
          <flux:radio value="sepomex" label="SEPOMEX" />
          <flux:radio value="personal" label="Personal" />
        </flux:radio.group>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
          <template x-for="doc in docs[medio_envio]" :key="doc.label">
            <a :href="doc.url"
               class="group relative block rounded-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-4 hover:ring-emerald-400/60 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 transition">
              <div class="flex items-start gap-3">
                <div class="shrink-0 mt-0.5 rounded-xl p-3 bg-emerald-100 dark:bg-emerald-900/40 ring-1 ring-emerald-500/20">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>
                  </svg>
                </div>
                <div class="min-w-0">
                  <h4 class="text-sm font-semibold text-neutral-900 dark:text-white truncate" x-text="doc.label"></h4>
                  <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5" x-text="doc.hint"></p>
                </div>
                <div class="ml-auto opacity-60 group-hover:opacity-100 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 3v12m0 0 4-4m-4 4-4-4"/><path d="M5 21h14"/>
                  </svg>
                </div>
              </div>
            </a>
          </template>
        </div>

        <div class="flex pb-2 justify-end">
          <flux:modal.close>
            <flux:button variant="ghost">Cerrar</flux:button>
          </flux:modal.close>
        </div>
      </div>
    </flux:modal>
  @endif

  <!-- TAB 1: Solicitud -->
  <section
    x-show="tab==='invitaciones'"
    x-cloak
    x-transition.opacity.duration.100ms
    class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">

    <x-solicitud.section-header title="Detalles de la Solicitud">
      Revisa los datos capturados, asigna facilitador y consulta a los participantes. <br>
      <p class="text-emerald-600 font-bold">
        {{ $solicitud->folio_materia }}
      </p>
    </x-solicitud.section-header>

    @if ($solicitud->materia != 'familiar')
      <div class="flex pl-8 flex-col items-start text-sm text-neutral-600 dark:text-neutral-300 font-semibold space-y-1">
        <span>Materia:</span>
        <flux:radio.group wire:model.change="materia">
          <flux:radio value="civil" label="Civil" />
          <flux:radio value="mercantil" label="Mercantil" />
        </flux:radio.group>
      </div>
    @endif

    {{-- Lista de personas --}}
    @include('livewire.solicitud.includes.listas-personas')
  </section>

  <!-- TAB 2: Invitaciones / Sesiones -->
  <section
    x-show="tab==='solicitud'"
    x-cloak
    x-transition.opacity.duration.100ms
    class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">

    <livewire:solicitud.invitaciones-panel />
  </section>

</div>

{{-- CIVIL / MERCANTIL --}}
@if ($materia === "mercantil" || $materia === "civil")    
  <div class="grid grid-cols-2 gap-2 animate__animated animate__fadeIn"
       x-data="{
         tipo: @entangle('tipo').live,
         map: @js($docMapCivil),
         cargados: @entangle('documentosCargados').live,
         opciones: [],
         recompute() {
           const todos = this.map[this.tipo] ?? [];
           this.opciones = todos.filter(d => !this.cargados.includes(d));
         }
       }"
       x-init="$watch('tipo', () => recompute()); $watch('cargados', () => recompute());"
  >
    {{-- 1) Primer select: tipo --}}
    <flux:select wire:model.live="tipo" placeholder="Selecciona un tema de mediación civil">
      @foreach($tiposDisponibles as $tipoItem)
        <flux:select.option>{{ $tipoItem }}</flux:select.option>
      @endforeach
    </flux:select>

    {{-- 2) Segundo select: instantáneo con opciones generadas por Alpine --}}
    <div x-show="tipo" x-cloak>
      <flux:select
        placeholder="Selecciona un documento"
        @change="
          const v = $event.target.value;
          if (v) {
            $wire.set('documentoSeleccionado', v);     // dispara updatedDocumentoSeleccionado
            opciones = opciones.filter(o => o !== v);  // feedback instantáneo
            $event.target.selectedIndex = 0;           // reset visual
          }
        "
      >
        {{-- opciones dinámicas (instantáneas) --}}
        <template x-for="doc in opciones" :key="doc">
          <option :value="doc" x-text="doc"></option>
        </template>

        {{-- estado vacío --}}
        <template x-if="opciones.length === 0">
          <option disabled>Sin documentos para este tema</option>
        </template>
      </flux:select>

      {{-- LOADER: aparece mientras se agrega el dropzone --}}
      <div class="mt-1 text-xs text-zinc-500" wire:loading.delay wire:target="documentoSeleccionado">
        Agregando…
      </div>
    </div>
  </div>

  {{-- Documentos ya seleccionados (con Dropzone) --}}
  @if (!empty($documentosCargados))
    @foreach($documentosCargados as $index => $doc)
      <div class="relative mb-4" wire:key="civil-{{ $doc }}-{{ $index }}">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 m-2">{{ $doc }}</label>

        <button
          wire:click="eliminarDocumento('{{ $doc }}')"
          wire:loading.attr="disabled"
          wire:target="eliminarDocumento('{{ $doc }}')"
          type="button"
          class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition disabled:opacity-50"
          title="Eliminar documento"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        {{-- LOADER de eliminación por ítem --}}
        <div class="mt-1 text-xs text-zinc-500"
             wire:loading.delay
             wire:target="eliminarDocumento('{{ $doc }}')">
          Eliminando…
        </div>

        <livewire:dropzone
          wire:model="archivosSubidos.{{ $index }}"
          :rules="['mimes:pdf','max:10240']"
          :multiple="false"
          :key="'dz-civil-'.$doc.'-'.$index"
        />
      </div>
    @endforeach
  @endif
@endif


{{-- FAMILIAR --}}
@if ($materia === "familiar")
  <div class="grid grid-cols-2 gap-2 animate__animated animate__fadeIn"
       x-data="{
         tema: @entangle('temaFamiliar').live,
         map: @js($docMapFamiliar),
         cargados: @entangle('documentosFamiliaresCargados').live,
         opciones: [],
         recompute() {
           const todos = this.map[this.tema] ?? [];
           this.opciones = todos.filter(d => !this.cargados.includes(d));
         }
       }"
       x-init="$watch('tema', () => recompute()); $watch('cargados', () => recompute());"
  >
    {{-- 1) Tema familiar --}}
    <flux:select wire:model.live="temaFamiliar" placeholder="Selecciona un tema de mediación familiar">
      @foreach($temasFamiliaresDisponibles as $tema)
        <flux:select.option>{{ $tema }}</flux:select.option>
      @endforeach
    </flux:select>

    {{-- 2) Documentos (instantáneo) --}}
    <div x-show="tema" x-cloak>
      <flux:select
        placeholder="Selecciona un documento"
        @change="
          const v = $event.target.value;
          if (v) {
            $wire.set('documentosFamiliarSeleccionado', v); // dispara updatedDocumentosFamiliarSeleccionado
            opciones = opciones.filter(o => o !== v);
            $event.target.selectedIndex = 0;
          }
        "
      >
        <template x-for="doc in opciones" :key="doc">
          <option :value="doc" x-text="doc"></option>
        </template>

        <template x-if="opciones.length === 0">
          <option disabled>Sin documentos para este tema</option>
        </template>
      </flux:select>

      {{-- LOADER: aparece mientras se agrega el dropzone --}}
      <div class="mt-1 text-xs text-zinc-500" wire:loading.delay wire:target="documentosFamiliarSeleccionado">
        Agregando…
      </div>
    </div>
  </div>

  {{-- Documentos familiares ya seleccionados (con Dropzone) --}}
  @if (!empty($documentosFamiliaresCargados))
    @foreach($documentosFamiliaresCargados as $index => $doc)
      <div class="relative mb-4" wire:key="fam-{{ $doc }}-{{ $index }}">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 m-2">{{ $doc }}</label>

        <button
          wire:click="eliminarDocumentoFamiliar('{{ $doc }}')"
          wire:loading.attr="disabled"
          wire:target="eliminarDocumentoFamiliar('{{ $doc }}')"
          type="button"
          class="absolute top-2 right-2 text-red-500 hover:text-red-700 transition disabled:opacity-50"
          title="Eliminar documento"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>

        {{-- LOADER de eliminación por ítem --}}
        <div class="mt-1 text-xs text-zinc-500"
             wire:loading.delay
             wire:target="eliminarDocumentoFamiliar('{{ $doc }}')">
          Eliminando…
        </div>

        <livewire:dropzone
          wire:model="archivosFamiliaresSubidos.{{ $index }}"
          :rules="['mimes:pdf','max:10240']"
          :multiple="false"
          :key="'dz-fam-'.$doc.'-'.$index"
        />
      </div>
    @endforeach
  @endif
@endif

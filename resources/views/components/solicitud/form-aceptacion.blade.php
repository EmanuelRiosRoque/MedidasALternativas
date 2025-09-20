@props([
  'textoPregunta' => '¿Aceptó mediación?',
  'ultimaNum' => null,
  'mostrarPreguntaAceptacion' => false,
])

@if ($mostrarPreguntaAceptacion)
  <div class="mt-0">
    <div class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
         x-data="{ valor: @entangle('aceptoProceso').live }">
      <div class="flex items-center justify-between">
        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ $textoPregunta }}</p>
        <span class="text-[10px] text-neutral-500">Aplica a #{{ $ultimaNum ?? '—' }}</span>
      </div>

      <div class="mt-2 flex items-center gap-6 text-sm">
        <label class="flex items-center gap-2">
          <input type="radio" wire:model="aceptoProceso" x-model="valor" class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500" value="1">
          <span>Sí</span>
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" wire:model="aceptoProceso" x-model="valor" class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500" value="0">
          <span>No</span>
        </label>
      </div>

      {{-- Motivo cancelación si NO --}}
      <div class="mt-3" x-show="valor == 0" x-cloak>
        <flux:select wire:model="motivoCancelacion" placeholder="Seleccione un motivo...">
          <flux:select.option value="1">No le interesa la mediación</flux:select.option>
          <flux:select.option value="2">Tenía otro compromiso</flux:select.option>
          <flux:select.option value="3">No confía en el proceso</flux:select.option>
          <flux:select.option value="4">Otro</flux:select.option>
        </flux:select>
        @error('motivoCancelacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
      </div>

      {{-- Manifestaciones si SÍ --}}
      <div class="mt-3 space-y-4" x-show="valor == 1" x-cloak>
        <p class="text-sm text-neutral-700 dark:text-neutral-300">
          Adjunta las <span class="font-medium">manifestaciones</span> (PDF). Estos archivos se guardarán al
          presionar <span class="font-medium">Confirmar</span>.
        </p>

        <div class="w-full">
          <div class="flex items-center justify-between mb-1 w-full">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
              Manifestaciones (PDF) *
            </label>

            <a href="{{ route('manifestacion.download', $solicitudId) }}" target="_blank" rel="noopener"
              class="inline-flex items-center gap-2 rounded-md border border-emerald-600 bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Generar documentos
            </a>
          </div>

          <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10420']" :multiple="true" class="w-full" />
          @error('manifestaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

          <div wire:loading wire:target="manifestaciones" class="mt-1 text-xs text-neutral-500">
            Subiendo documentos…
          </div>
        </div>
      </div>

      <div class="mt-3">
        <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
      </div>
    </div>
  </div>
@endif

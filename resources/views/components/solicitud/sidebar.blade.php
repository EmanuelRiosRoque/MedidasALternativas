@props([
  'isPreMediacion' => true,
  'etiquetaEtapa2' => 'Mediación',
  'ultima' => null,
  'next' => 1,
  'etqUnidadSing' => 'invitación',
  'bloqueoNueva' => false,
  'mostrarPreguntaAceptacion' => false,
  'bloqueoAsistencia' => false,
  'motivoBloqueo' => '',
  'tooltipMsg' => '',
  'textoBtn' => 'Enviar invitación',
  'accionClick' => 'nueva',
])

<aside class="md:col-span-4">
  <div class="md:sticky md:top-4 md:self-start space-y-4">
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
      <div class="text-sm font-semibold mb-2">Resumen</div>
      <dl class="text-sm space-y-2">
        <div class="flex justify-between">
          <dt class="text-neutral-500">Etapa</dt>
          <dd class="font-medium">{{ $isPreMediacion ? 'Pre-mediación' : $etiquetaEtapa2 }}</dd>
        </div>
        <div class="flex justify-between">
          <dt class="text-neutral-500">Siguiente #</dt>
          <dd class="font-medium">#{{ $next }}</dd>
        </div>
        @if($ultima)
          <div class="flex justify-between">
            <dt class="text-neutral-500">Última {{ $etqUnidadSing }}</dt>
            <dd>#{{ $ultima->numero_inv }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-neutral-500">Asistencia</dt>
            <dd>{{ is_null($ultima->asistio) ? 'Pendiente' : ($ultima->asistio ? 'Sí' : 'No') }}</dd>
          </div>
        @endif
      </dl>
    </div>

    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
      @php
        $tooltip = $tooltipMsg ?: ($mostrarPreguntaAceptacion ? 'Primero registra la aceptación / rechazo.' : ($bloqueoAsistencia ? 'Primero registra la asistencia.' : $motivoBloqueo));
      @endphp

      @if($bloqueoNueva || $mostrarPreguntaAceptacion || $bloqueoAsistencia)
        <flux:tooltip content="{{ $tooltip }}">
          <div>
            <flux:button class="w-full" disabled variant="primary" icon="lock-closed">{{ $textoBtn }}</flux:button>
          </div>
        </flux:tooltip>
      @else
        <flux:button class="w-full" wire:click="store('{{ $accionClick }}')" variant="primary" wire:loading.attr="disabled">
          {{ $textoBtn }}
        </flux:button>
      @endif
      <div wire:loading wire:target="store" class="mt-2 text-xs text-neutral-500">Guardando…</div>
    </div>
  </div>
</aside>

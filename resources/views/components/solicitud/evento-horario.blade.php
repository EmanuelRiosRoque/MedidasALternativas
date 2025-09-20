@props([
  'evento' => null,
  'mostrarFecha' => true,   // <x-solicitud.evento-horario :mostrar-fecha="true" />
])

@php
  $fmt = fn($t) => $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—';
  $sep = (int)($evento->opcion_invitacion ?? 1) === 0; // 0=separados
@endphp

@if($evento)
  <div class="space-y-3">
    @if($mostrarFecha)
      <div class="flex items-center gap-2">
        <flux:icon.calendar class="w-4 h-4 text-emerald-600" />
        <span class="font-medium">Fecha:</span>
        <span class="font-semibold">{{ $evento->fecha ?? '—' }}</span>
      </div>
    @endif

    @if ($sep)
      <div class="grid sm:grid-cols-2 gap-3">
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
          <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
          <p class="text-sm font-semibold">{{ $fmt($evento->hora_inicio) }} - {{ $fmt($evento->hora_fin) }}</p>
        </div>
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
          <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
          <p class="text-sm font-semibold">{{ $fmt($evento->hora_inicio_invitado) }} - {{ $fmt($evento->hora_fin_invitado) }}</p>
        </div>
      </div>
    @else
      <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
        <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
        <p class="text-sm font-semibold">{{ $fmt($evento->hora_inicio) }} - {{ $fmt($evento->hora_fin) }}</p>
      </div>
    @endif
  </div>
@else
  <p class="text-xs text-neutral-500">Sin evento asignado.</p>
@endif

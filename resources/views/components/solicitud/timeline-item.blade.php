@props([
  'inv',                       // Invitacion
  'isPre' => true,             // ¿pre-mediación?
  'evento' => null,            // evento #1 de pre
  'eventoSegPre' => null,      // evento #2 de pre (reasignación)
  'eventoMed' => null,         // evento de mediación
])

@php
  $fmt = fn($t) => $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—';

  // Resolver evento asociado
  $evForInv = $inv->evento ?? null;
  if (!$evForInv) {
    if ($isPre) {
      $evForInv = ($inv->numero_inv == 2) ? ($eventoSegPre ?? $evento) : $evento;
    } else {
      $evForInv = $eventoMed;
    }
  }

  $evSep   = (int)($evForInv->opcion_invitacion ?? 1) === 0;
  $hEvIni  = $evForInv->hora_inicio ?? null;
  $hEvFin  = $evForInv->hora_fin ?? null;
  $hEvIniI = $evForInv->hora_inicio_invitado ?? null;
  $hEvFinI = $evForInv->hora_fin_invitado ?? null;

  $chip = is_null($inv->asistio)
    ? ['Pendiente','bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300','bg-amber-400']
    : ($inv->asistio
        ? ['Asistió','bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200','bg-emerald-500']
        : ['No asistió','bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200','bg-rose-500']);
@endphp

<div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
  <span class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $chip[2] }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

  <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
    <div class="flex items-start justify-between gap-3">
      <div class="text-sm font-semibold">{{ $isPre ? 'Invitación' : 'Sesión' }} #{{ $inv->numero_inv }}</div>
      <div class="flex items-center gap-2">
        <span class="text-[10px] px-2 py-1 rounded-full {{ $chip[1] }}">{{ $chip[0] }}</span>

        @if (!is_null($inv->acepta_proceso))
          <span class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
            {{ $isPre ? ($inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó') : ($inv->acepta_proceso ? 'Con acuerdo' : 'Sin acuerdo') }}
          </span>
        @endif

        <button type="button" class="text-xs px-2 py-1 rounded-md bg-emerald-600 text-white border border-emerald-600 hover:bg-emerald-700 transition inline-flex items-center gap-1" @click="open = !open">
          <span x-show="!open">Ver detalles</span>
          <span x-show="open" x-cloak>Ocultar</span>
        </button>
      </div>
    </div>

    <div x-show="open" x-collapse x-cloak>
      {{-- Evento --}}
      @if ($evForInv)
        <div class="mt-3 rounded-lg border border-emerald-200 dark:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-900/10 p-3">
          <div class="flex items-center justify-between gap-2">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">Evento (agenda)</p>
            @if($evForInv->fecha)
              <span class="text-xs text-neutral-600 dark:text-neutral-300">
                Fecha de evento: <span class="font-medium">{{ $evForInv->fecha }}</span>
              </span>
            @endif
          </div>

          <div class="mt-2 text-sm">
            @if(!$evSep)
              @if ($hEvIni && $hEvFin)
                <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2 inline-block">
                  <p class="text-[11px] text-neutral-500">Horario (evento)</p>
                  <p class="text-sm font-medium">{{ $fmt($hEvIni) }} - {{ $fmt($hEvFin) }}</p>
                </div>
              @else
                <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
              @endif
            @else
              <div class="grid sm:grid-cols-2 gap-3">
                <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2">
                  <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                  <p class="text-sm font-medium">{{ $hEvIni ? $fmt($hEvIni) : '—' }} - {{ $hEvFin ? $fmt($hEvFin) : '—' }}</p>
                </div>
                <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2">
                  <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                  <p class="text-sm font-medium">{{ $hEvIniI ? $fmt($hEvIniI) : '—' }} - {{ $hEvFinI ? $fmt($hEvFinI) : '—' }}</p>
                </div>
              </div>
            @endif
          </div>
        </div>
      @endif

      {{-- Invitación/sesión --}}
      <div class="mt-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-3">
        <div class="flex items-center justify-between gap-2">
          <p class="text-sm font-semibold">{{ $isPre ? 'Invitación' : 'Sesión' }}</p>
          @php $fa = $inv->modalidad === 'linea' ? ($inv->fecha_atencion ?: $inv->fecha_envio) : ($inv->fecha_envio ?: $inv->fecha_atencion); @endphp
          <span class="text-xs text-neutral-600 dark:text-neutral-300">
            {{ $inv->modalidad === 'linea' ? 'Fecha de atención' : 'Fecha de envío' }}:
            <span class="font-medium">{{ $fa ?: '—' }}</span>
          </span>
        </div>

        @if ($inv->modalidad === 'linea')
          <div class="mt-2 text-sm">
            <span class="font-medium">URL:</span>
            @if ($inv->url)
              <a class="text-emerald-600 underline" href="{{ $inv->url }}" target="_blank" rel="noopener">Abrir</a>
            @else
              <span class="text-neutral-500">—</span>
            @endif
          </div>
        @endif

        <div class="mt-2 text-sm">
          @if ((int)($inv->acudiran_juntos ?? 1) === 1)
            <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
              <p class="text-[11px] text-neutral-500">{{ $inv->modalidad === 'linea' ? 'Horario (invitación)' : 'Hora envío (capturada)' }}</p>
              <p class="text-sm font-medium">
                {{ $inv->hora_inicio ? $fmt($inv->hora_inicio) : '—' }}
                @if($inv->hora_fin) - {{ $fmt($inv->hora_fin) }} @endif
              </p>
            </div>
          @else
            <div class="grid sm:grid-cols-2 gap-3">
              <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                <p class="text-sm font-medium">{{ $inv->hora_inicio ? $fmt($inv->hora_inicio) : '—' }} - {{ $inv->hora_fin ? $fmt($inv->hora_fin) : '—' }}</p>
              </div>
              <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                <p class="text-sm font-medium">{{ $inv->hora_inicio_invitado ? $fmt($inv->hora_inicio_invitado) : '—' }} - {{ $inv->hora_fin_invitado ? $fmt($inv->hora_fin_invitado) : '—' }}</p>
              </div>
            </div>
          @endif
        </div>

        <div class="mt-2 text-xs">
          <span class="px-2 py-1 rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
            Facilitador (invitación): {{ optional($inv->facilitador)->nombre ?? '—' }}
          </span>
        </div>
      </div>

      {{-- Acciones de asistencia (delegadas al padre Livewire) --}}
      <div class="mt-3 text-sm border-t border-neutral-100 dark:border-neutral-800 pt-3">
        <div class="flex items-center justify-between">
          <div>
            <span class="font-medium">¿Asistió?:</span>
            @if (is_null($inv->asistio))
              <span class="text-neutral-500">Sin registrar</span>
            @else
              <span class="{{ $inv->asistio ? 'text-emerald-600' : 'text-rose-600' }}">{{ $inv->asistio ? 'Sí' : 'No' }}</span>
            @endif
          </div>
          <div class="flex gap-2">
            @if (is_null($inv->asistio))
              <flux:button size="xs" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, true)">Sí asistió</flux:button>
              <flux:button size="xs" variant="danger" wire:click="marcarAsistencia({{ $inv->id }}, false)">No asistió</flux:button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

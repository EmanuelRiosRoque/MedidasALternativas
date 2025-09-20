@props([
  'invitaciones',
  'isPreMediacion' => true,
  'evento' => null,
  'eventoSegPreMedicion' => null,
  'eventoMediacion' => null,
  'modalidad' => 'linea',
  'fmtHora' => null,
])

<div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm">
  <div class="flex items-center justify-between mb-4">
    <h4 class="font-semibold text-neutral-800 dark:text-neutral-100">Historial</h4>
    <div class="text-sm text-neutral-500">Total: {{ $invitaciones->count() }} {{ $isPreMediacion ? 'invitaciones' : 'sesiones' }}</div>
  </div>

  @if($invitaciones->isEmpty())
    <p class="text-sm text-neutral-600 dark:text-neutral-300">Aún no hay {{ $isPreMediacion ? 'invitaciones' : 'sesiones' }} registradas.</p>
  @else
    <div class="relative pl-6">
      <span class="absolute left-2 top-1 bottom-1 w-px bg-neutral-200 dark:bg-neutral-700"></span>

      @foreach($invitaciones as $inv)
        @php
          $fecha = $inv->fecha_atencion ?: $inv->fecha_envio;

          $chipA = is_null($inv->asistio)
            ? ['Pendiente','bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300']
            : ($inv->asistio
              ? ['Asistió','bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200']
              : ['No asistió','bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200']);
          $dot = is_null($inv->asistio) ? 'bg-amber-400' : ($inv->asistio ? 'bg-emerald-500' : 'bg-rose-500');

          $evForInv = $inv->evento ?? null;
          if (!$evForInv) {
            if ($isPreMediacion) {
              if ($inv->numero_inv == 1)      $evForInv = $evento ?? null;
              elseif ($inv->numero_inv == 2)  $evForInv = $eventoSegPreMedicion ?? ($evento ?? null);
              else                             $evForInv = $evento ?? null;
            } else {
              $evForInv = $eventoMediacion ?? null;
            }
          }

          $hInvIni  = $inv->hora_inicio;
          $hInvFin  = $inv->hora_fin;
          $hInvIniI = $inv->hora_inicio_invitado;
          $hInvFinI = $inv->hora_fin_invitado;
          $invJuntos = (int)($inv->acudiran_juntos ?? 1) === 1;

          $hEvIni  = $evForInv->hora_inicio ?? null;
          $hEvFin  = $evForInv->hora_fin ?? null;
          $hEvIniI = $evForInv->hora_inicio_invitado ?? null;
          $hEvFinI = $evForInv->hora_fin_invitado ?? null;
          $evSeparados = (int)($evForInv->opcion_invitacion ?? 1) === 0;

          $facEvento = optional($evForInv->facilitador ?? null)->nombre ?? null;
        @endphp

        <div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
          <span class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $dot }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

          <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
            <div class="flex items-start justify-between gap-3">
              <div class="text-sm font-semibold">{{ $isPreMediacion ? 'Invitación' : 'Sesión' }} #{{ $inv->numero_inv }}</div>
              <div class="flex items-center gap-2">
                <span class="text-[10px] px-2 py-1 rounded-full {{ $chipA[1] }}">{{ $chipA[0] }}</span>

                @if (!is_null($inv->acepta_proceso))
                  <span class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                    {{ $isPreMediacion ? ($inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó') : ($inv->acepta_proceso ? 'Con acuerdo' : 'Sin acuerdo') }}
                  </span>
                @endif

                <button type="button" class="text-xs px-2 py-1 rounded-md bg-emerald-600 text-white border border-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 transition inline-flex items-center gap-1"
                        @click="open = !open" :aria-expanded="open.toString()">
                  <span x-show="!open">Ver detalles</span>
                  <span x-show="open" x-cloak>Ocultar</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <div x-show="open" x-collapse x-cloak>
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
                    @if (!$evSeparados)
                      @if ($hEvIni && $hEvFin)
                        <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2 inline-block">
                          <p class="text-[11px] text-neutral-500">Horario (evento)</p>
                          <p class="text-sm font-medium">{{ $fmtHora($hEvIni) }} - {{ $fmtHora($hEvFin) }}</p>
                        </div>
                      @else
                        <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
                      @endif
                    @else
                      <div class="grid sm:grid-cols-2 gap-3">
                        <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2">
                          <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                          <p class="text-sm font-medium">{{ $hEvIni ? $fmtHora($hEvIni) : '—' }} - {{ $hEvFin ? $fmtHora($hEvFin) : '—' }}</p>
                        </div>
                        <div class="rounded-md border border-emerald-200 dark:border-emerald-700 p-2">
                          <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                          <p class="text-sm font-medium">{{ $hEvIniI ? $fmtHora($hEvIniI) : '—' }} - {{ $hEvFinI ? $fmtHora($hEvFinI) : '—' }}</p>
                        </div>
                      </div>
                    @endif
                  </div>

                  @if($facEvento)
                    <div class="mt-2 text-xs">
                      <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-700/20 dark:text-emerald-300">
                        Facilitador (evento): {{ $facEvento }}
                      </span>
                    </div>
                  @endif
                </div>
              @endif

              <div class="mt-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-3">
                <div class="flex items-center justify-between gap-2">
                  <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">{{ $isPreMediacion ? 'Invitación' : 'Sesión' }}</p>
                  @if($inv->modalidad === 'linea')
                    <span class="text-xs text-neutral-600 dark:text-neutral-300">Fecha de atención: <span class="font-medium">{{ $inv->fecha_atencion ?: $inv->fecha_envio ?: '—' }}</span></span>
                  @else
                    <span class="text-xs text-neutral-600 dark:text-neutral-300">Fecha de envío: <span class="font-medium">{{ $inv->fecha_envio ?: $inv->fecha_atencion ?: '—' }}</span></span>
                  @endif
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
                    @if ($inv->hora_inicio || $inv->hora_fin || $modalidad === 'presnecial')
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                        <p class="text-[11px] text-neutral-500">{{ $inv->modalidad === 'linea' ? 'Horario (invitación)' : 'Hora envío (capturada)' }}</p>
                        <p class="text-sm font-medium">
                          {{ $inv->hora_inicio ? $fmtHora($inv->hora_inicio) : '—' }}
                          @if($inv->hora_fin) - {{ $fmtHora($inv->hora_fin) }} @endif
                        </p>
                      </div>
                    @elseif($modalidad === 'linea')
                      @if (!$evSeparados)
                        @if ($hEvIni && $hEvFin)
                          <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                            <p class="text-[11px] text-neutral-500">Horario (evento)</p>
                            <p class="text-sm font-medium">{{ $fmtHora($hEvIni) }} - {{ $fmtHora($hEvFin) }}</p>
                          </div>
                        @else
                          <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
                        @endif
                      @else
                        <div class="grid sm:grid-cols-2 gap-3">
                          <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                            <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                            <p class="text-sm font-medium">{{ $hEvIni ? $fmtHora($hEvIni) : '—' }} - {{ $hEvFin ? $fmtHora($hEvFin) : '—' }}</p>
                          </div>
                          <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                            <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                            <p class="text-sm font-medium">{{ $hEvIniI ? $fmtHora($hEvIniI) : '—' }} - {{ $hEvFinI ? $fmtHora($hEvFinI) : '—' }}</p>
                          </div>
                        </div>
                      @endif
                    @endif
                  @else
                    <div class="grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">{{ $inv->hora_inicio ? $fmtHora($inv->hora_inicio) : '—' }} - {{ $inv->hora_fin ? $fmtHora($inv->hora_fin) : '—' }}</p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">{{ $inv->hora_inicio_invitado ? $fmtHora($inv->hora_inicio_invitado) : '—' }} - {{ $inv->hora_fin_invitado ? $fmtHora($inv->hora_fin_invitado) : '—' }}</p>
                      </div>
                    </div>
                  @endif
                </div>

                <div class="mt-2 text-xs">
                  <span class="px-2 py-1 rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                    Facilitador (invitación): {{ optional($inv->facilitador)->nombre ?? '—' }}
                  </span>
                  @if ($evForInv && $inv->facilitador && optional($evForInv->facilitador)->nombre !== optional($inv->facilitador)->nombre)
                    <span class="ml-2 px-2 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-700/20 dark:text-amber-300">* Distinto al del evento</span>
                  @endif
                </div>
              </div>

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
      @endforeach
    </div>
  @endif
</div>

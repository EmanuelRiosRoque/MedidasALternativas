<div class="space-y-6">

  {{-- ========= FORMULARIO ========= --}}
  <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
    @php
      $isPrimera     = $invitaciones->isEmpty();
      $next          = (($invitaciones->max('numero_inv') ?? 0) + 1);
      $ultima        = $invitaciones->sortByDesc('numero_inv')->first();
      $bloqueoNueva  = ($ultima && is_null($ultima->asistio));  // bloquea si falta registrar asistencia de la última

      $accionClick   = $isPrimera ? 'primera' : 'nueva';
      $textoBtn      = $isPrimera ? 'Crear invitación' : 'Crear nueva invitación';

      $fechaAtEvento  = $evento->fecha ?? null;
      $opcionSeparados= (int)($evento->opcion_invitacion ?? 1) === 0; // 0=separados, 1=juntos
      $fmtHora = function($t) {
        return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—';
      };
    @endphp

    <h3 class="text-base font-semibold mb-3 text-neutral-800 dark:text-neutral-100">
      {{ $isPrimera ? 'Crear primera invitación' : 'Crear nueva invitación (#'.$next.')' }}
    </h3>

    @if ($bloqueoNueva)
      <flux:callout icon="exclamation-triangle" color="amber" class="mb-3">
        <flux:callout.heading>Acción requerida</flux:callout.heading>
        <flux:callout.text>
          Antes de crear una nueva invitación, registra si la invitación #{{ $ultima->numero_inv }} tuvo asistencia o no.
        </flux:callout.text>
      </flux:callout>
    @endif

    {{-- ====== EN LÍNEA ====== --}}
    @if ($modalidad === 'linea')

      @if ($isPrimera)
        {{-- 1ª en línea: lectura (fecha/horarios del evento) + input ÚNICO URL --}}
        <flux:callout icon="computer-desktop" color="neutral" class="mb-3">
          <flux:callout.heading>Detalles de la primera invitación (del evento)</flux:callout.heading>
          <flux:callout.text class="space-y-1">
            <div>
              <span class="font-medium">Fecha de atención:</span>
              {{ $fechaAtEvento ?? '—' }}
            </div>

            @if ($opcionSeparados)
              <div class="mt-2">
                <div><span class="font-medium">Horario solicitante:</span> {{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}</div>
                <div><span class="font-medium">Horario invitado:</span> {{ $fmtHora($horaInicioInvitado) }} - {{ $fmtHora($horaFinInvitado) }}</div>
              </div>
            @else
              <div class="mt-2">
                <span class="font-medium">Horario:</span> {{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}
              </div>
            @endif
          </flux:callout.text>
        </flux:callout>

        <div class="grid md:grid-cols-2 gap-3">
          <flux:input
            label="Enlace / Liga"
            placeholder="https://meet.google.com/..."
            wire:model="enlaceReunion" />
        </div>

      @else
        {{-- N-ésima en línea: URL + Fecha de atención (nueva); horarios editables --}}
        <div class="grid md:grid-cols-2 gap-3">
          <flux:input
            label="Enlace / Liga (nueva)"
            placeholder="https://meet.google.com/..."
            wire:model="urlNuevaInv" />
          <flux:input
            label="Fecha de atención (nueva)"
            type="date"
            wire:model="fechaNuevaInv" />
        </div>

        <div class="mt-4">
          @if ($opcionSeparados)
            {{-- Separados --}}
            <div class="space-y-3">
              <p class="text-sm font-semibold dark:text-white">Horario para solicitante(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model="horaFin" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model="horaInicioInvitado" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model="horaFinInvitado" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>
            </div>
          @else
            {{-- Juntos --}}
            <div class="grid grid-cols-2 gap-2 mt-2">
              <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                @endforeach
              </flux:select>
              <flux:select wire:model="horaFin" placeholder="Hora fin">
                @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                @endforeach
              </flux:select>
            </div>
          @endif
        </div>
      @endif

    {{-- ====== PRESENCIAL ====== --}}
    @else
      @if ($isPrimera)
        {{-- 1ª presencial: INPUT para fecha_envio --}}
        <div class="grid md:grid-cols-2 gap-3">
          <flux:input
            label="Fecha de envío"
            type="date"
            wire:model="fechaEnvio" />
        </div>
      @else
        {{-- N-ésima presencial: INPUT para fecha_envio (nueva) --}}
        <div class="grid md:grid-cols-2 gap-3">
          <flux:input
            label="Fecha de envío (nueva)"
            type="date"
            wire:model="fechaNuevaInv" />
        </div>
      @endif
    @endif

    {{-- Botón guardar / bloqueado --}}
    <div class="flex justify-end mt-4">
      @if($bloqueoNueva)
        <flux:tooltip content="Primero registra la asistencia de la última invitación.">
          <div>
            <flux:button disabled variant="primary" icon="lock-closed">
              {{ $textoBtn }}
            </flux:button>
          </div>
        </flux:tooltip>
      @else
        <flux:button
          wire:click="store('{{ $accionClick }}')"
          variant="primary"
          wire:loading.attr="disabled"
        >
          {{ $textoBtn }}
        </flux:button>
      @endif
    </div>

    <div wire:loading wire:target="store" class="mt-3 text-sm text-neutral-500">
      Guardando…
    </div>
  </div>

  {{-- ========= LISTADO ========= --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    @forelse ($invitaciones as $inv)
      <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4" wire:key="inv-{{ $inv->id }}">
        <div class="flex items-center justify-between">
          <div class="text-sm font-semibold">Invitación #{{ $inv->numero_inv }}</div>
          <span class="text-xs px-2 py-1 rounded-full
            {{ ($inv->estatus_id == 8) ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300' }}">
            {{ $estatusLabels[$inv->estatus_id] ?? '—' }}
          </span>
        </div>

        @if ($modalidad === 'linea')
          {{-- EN LÍNEA --}}
          <div class="mt-2 text-sm space-y-1">
            <div><span class="font-medium">Fecha de atención:</span> {{ $inv->fecha_atencion ?? '—' }}</div>
            <div>
              <span class="font-medium">URL:</span>
              @if ($inv->url)
                <a class="text-emerald-600 underline" href="{{ $inv->url }}" target="_blank" rel="noopener">Abrir</a>
              @else
                —
              @endif
            </div>

            @php
              $juntosCard = (int)($inv->acudiran_juntos ?? 1) === 1;
              $fmtCard = function($t) { return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—'; };
            @endphp

            @if ($juntosCard)
              <div><span class="font-medium">Horario:</span> {{ $fmtCard($inv->hora_inicio) }} - {{ $fmtCard($inv->hora_fin) }}</div>
            @else
              <div><span class="font-medium">Horario solicitante:</span> {{ $fmtCard($inv->hora_inicio) }} - {{ $fmtCard($inv->hora_fin) }}</div>
              <div><span class="font-medium">Horario invitado:</span> {{ $fmtCard($inv->hora_inicio_invitado) }} - {{ $fmtCard($inv->hora_fin_invitado) }}</div>
            @endif
          </div>
        @else
          {{-- PRESENCIAL --}}
          <div class="mt-2 text-sm">
            <span class="font-medium">Fecha de envío:</span> {{ $inv->fecha_envio ?? '—' }}
          </div>
        @endif

        {{-- Controles de asistencia --}}
        <div class="mt-3 text-sm">
          <span class="font-medium">¿Asistió?:</span>
          @if (is_null($inv->asistio))
            <span class="text-neutral-500">Sin registrar</span>
            <div class="mt-2 flex gap-2">
              <flux:button size="sm" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, true)">Sí asistió</flux:button>
              <flux:button size="sm" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, false)">No asistió</flux:button>
            </div>
          @else
            <span class="{{ $inv->asistio ? 'text-emerald-600' : 'text-red-600' }}">
              {{ $inv->asistio ? 'Sí' : 'No' }}
            </span>
            <flux:button size="xs" variant="ghost" class="ml-2" wire:click="marcarAsistencia({{ $inv->id }}, {{ $inv->asistio ? 'false' : 'true' }})">
              Cambiar
            </flux:button>
          @endif
        </div>
      </div>
    @empty
      <div class="text-sm italic text-neutral-500">Aún no hay invitaciones.</div>
    @endforelse
  </div>
</div>

<div class="space-y-8 max-w-6xl mx-auto">

  {{-- ========= FORMULARIO ========= --}}
  <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm">
    @php
      $isPrimera      = $invitaciones->isEmpty();
      $next           = (($invitaciones->max('numero_inv') ?? 0) + 1);
      $ultima         = $invitaciones->sortByDesc('numero_inv')->first();

      // Límite por tipo de proceso
      $isPreMediacion = ((int)($tipoProcesoId ?? 0) === 1);
      $bloqueoLimite  = $isPreMediacion && $next > 2;

      // Bloqueos existentes
      $bloqueoAsistencia     = ($ultima && is_null($ultima->asistio));                               // falta asistencia
      $requiereAceptarAntes  = ($ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso)); // asistió y falta "acepta_proceso"
      $bloqueoPorAcepto      = ($ultima && $ultima->asistio === 1 && (int)$ultima->acepta_proceso === 1); // ya aceptó

      $bloqueoNueva = $bloqueoLimite || $bloqueoAsistencia || $requiereAceptarAntes || $bloqueoPorAcepto;

      $motivoBloqueo = $bloqueoLimite
          ? 'En Pre-mediación solo se permiten 2 invitaciones.'
          : ($bloqueoAsistencia
              ? 'Primero registra la asistencia de la última invitación.'
              : ($requiereAceptarAntes
                  ? 'Confirma si aceptaron mediación antes de crear otra invitación.'
                  : ($bloqueoPorAcepto ? 'Ya aceptaron mediación. No se permiten nuevas invitaciones.' : '')
                ));

      $accionClick   = $isPrimera ? 'primera' : 'nueva';
      $textoBtn      = $isPrimera ? 'Crear invitación' : 'Crear nueva invitación';

      $fechaAtEvento  = $evento->fecha ?? null;
      $opcionSeparados= (int)($evento->opcion_invitacion ?? 1) === 0; // 0=separados, 1=juntos
      $fmtHora = function($t) { return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—'; };

      // Chips de resumen
      $chipProceso = $isPreMediacion
        ? ['label' => 'Pre-mediación • máx. 2 invitaciones', 'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300']
        : ['label' => 'Otro proceso', 'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'];

      $chipAsistencia = is_null($ultima?->asistio)
        ? ['label' => 'Asistencia: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300']
        : ($ultima->asistio
            ? ['label' => 'Asistencia: Sí', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => 'Asistencia: No', 'class' => 'bg-red-100 text-red-700 dark:bg-red-700/20 dark:text-red-300']);

      $chipAcepto = is_null($ultima?->acepta_proceso)
        ? ['label' => 'Aceptación: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300']
        : ($ultima->acepta_proceso
            ? ['label' => 'Aceptó mediación', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300']
            : ['label' => 'No aceptó mediación', 'class' => 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300']);
    @endphp

    {{-- Resumen compacto --}}
    <div class="mb-4 flex flex-wrap items-center gap-2 text-[11px]">
      <span class="px-2 py-1 rounded-full {{ $chipProceso['class'] }}">{{ $chipProceso['label'] }}</span>
      @if (!$isPrimera)
        <span class="px-2 py-1 rounded-full {{ $chipAsistencia['class'] }}">{{ $chipAsistencia['label'] }}</span>
        @if ($ultima && $ultima->asistio === 1)
          <span class="px-2 py-1 rounded-full {{ $chipAcepto['class'] }}">{{ $chipAcepto['label'] }}</span>
        @endif
        <span class="text-neutral-400">•</span>
        <span class="text-neutral-500">Última invitación: #{{ $ultima->numero_inv }}</span>
      @endif
    </div>

    <div class="flex items-center justify-between mb-2">
      <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
        {{ $isPrimera ? 'Crear primera invitación' : 'Crear nueva invitación (#'.$next.')' }}
      </h3>
      @if($bloqueoNueva)
        <flux:tooltip content="{{ $motivoBloqueo }}">
          <span class="text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300">Bloqueada</span>
        </flux:tooltip>
      @endif
    </div>
    <div class="h-px bg-neutral-100 dark:bg-neutral-800 mb-4"></div>

    {{-- ====== EN LÍNEA ====== --}}
    @if ($modalidad === 'linea')

      @if ($isPrimera)
        <flux:callout icon="computer-desktop" color="neutral" class="mb-4">
          <flux:callout.heading>Detalles del evento</flux:callout.heading>
          <flux:callout.text class="space-y-1">
            <div><span class="font-medium">Fecha de atención:</span> {{ $fechaAtEvento ?? '—' }}</div>
            @if ($opcionSeparados)
              <div class="mt-2">
                <div><span class="font-medium">Horario solicitante:</span> {{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}</div>
                <div><span class="font-medium">Horario invitado:</span> {{ $fmtHora($horaInicioInvitado) }} - {{ $fmtHora($horaFinInvitado) }}</div>
              </div>
            @else
              <div class="mt-2"><span class="font-medium">Horario:</span> {{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}</div>
            @endif
          </flux:callout.text>
        </flux:callout>

        {{-- FORM: centrado --}}
        <div class="grid gap-3 max-w-lg mx-auto">
          <div>
            <flux:input label="Enlace / Liga" placeholder="https://meet.google.com/..." wire:model="enlaceReunion" />
            @error('enlaceReunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>

      @else
        {{-- FORM: centrado --}}
        <div class="grid md:grid-cols-2 gap-3 max-w-2xl mx-auto">
          <div>
            <flux:input label="Enlace / Liga (nueva)" placeholder="https://meet.google.com/..." wire:model="urlNuevaInv" />
            @error('urlNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <flux:input label="Fecha de atención (nueva)" type="date" wire:model="fechaNuevaInv" />
            @error('fechaNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>

        {{-- HORARIOS: centrado --}}
        <div class="mt-4 max-w-2xl mx-auto">
          @if ($opcionSeparados)
            <div class="space-y-3">
              <p class="text-sm font-semibold dark:text-white">Horario solicitante(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model="horaFin" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model="horaInicioInvitado" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model="horaFinInvitado" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
                </flux:select>
              </div>
            </div>
          @else
            <div class="grid grid-cols-2 gap-2">
              <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
              </flux:select>
              <flux:select wire:model="horaFin" placeholder="Hora fin">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option> @endforeach
              </flux:select>
            </div>
          @endif
        </div>
      @endif

    {{-- ====== PRESENCIAL ====== --}}
    @else
      @if ($isPrimera)
        {{-- FORM: centrado --}}
        <div class="grid gap-3 max-w-lg mx-auto">
          <div>
            <flux:input label="Fecha de envío" type="date" wire:model="fechaEnvio" />
            @error('fechaEnvio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>
      @else
        {{-- FORM: centrado --}}
        <div class="grid gap-3 max-w-lg mx-auto">
          <div>
            <flux:input label="Fecha de envío (nueva)" type="date" wire:model="fechaNuevaInv" />
            @error('fechaNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>
      @endif
    @endif

    {{-- ====== ¿Aceptó mediación? ====== --}}
    @if ($mostrarPreguntaAceptacion)
      <div class="mt-4 rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10 max-w-lg">
        <div class="flex items-center justify-between">
          <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">¿Aceptó mediación?</p>
          <span class="text-[10px] text-neutral-500">Aplica a #{{ $ultima->numero_inv ?? '—' }}</span>
        </div>

        <div class="mt-2 flex items-center gap-6 text-sm">
          <label class="flex items-center gap-2">
            <input type="radio" class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500"
                   wire:model="aceptoProceso" value="1">
            <span>Sí, aceptó</span>
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500"
                   wire:model="aceptoProceso" value="0">
            <span>No aceptó</span>
          </label>
        </div>

        <div class="mt-3">
          <flux:button size="sm" variant="primary" wire:click="confirmarAceptacionProceso">Confirmar</flux:button>
        </div>
      </div>
    @endif

    {{-- Acción principal (centrado) --}}
    <div class="flex justify-center mt-5">
      @if($bloqueoNueva)
        <flux:tooltip content="{{ $motivoBloqueo }}">
          <div>
            <flux:button disabled variant="primary" icon="lock-closed">
              {{ $textoBtn }}
            </flux:button>
          </div>
        </flux:tooltip>
      @else
        <flux:button wire:click="store('{{ $accionClick }}')" variant="primary" wire:loading.attr="disabled">
          {{ $textoBtn }}
        </flux:button>
      @endif
    </div>

    <div wire:loading wire:target="store" class="mt-3 text-sm text-neutral-500">
      Guardando…
    </div>
  </div>

  {{-- ========= LISTADO ========= --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse ($invitaciones as $inv)
      @php
        // Color lateral según estado
        $left = 'border-l-4 border-neutral-200';
        if (is_null($inv->asistio)) {
          $left = 'border-l-4 border-amber-400';
        } elseif ((int)$inv->asistio === 0) {
          $left = 'border-l-4 border-red-400';
        } elseif (!is_null($inv->acepta_proceso)) {
          $left = (int)$inv->acepta_proceso === 1 ? 'border-l-4 border-emerald-500' : 'border-l-4 border-sky-400';
        }
        $fmtCard = function($t){ return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—'; };
      @endphp

      <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 {{ $left }} p-4 bg-white dark:bg-neutral-900 shadow-sm hover:shadow-md transition-shadow" wire:key="inv-{{ $inv->id }}">
        <div class="flex items-center justify-between">
          <div class="text-sm font-semibold">Invitación #{{ $inv->numero_inv }}</div>
          <div class="flex items-center gap-2">
            @if (!is_null($inv->acepta_proceso))
              <span class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                {{ $inv->acepta_proceso ? 'Aceptó' : 'No aceptó' }}
              </span>
            @endif
            <span class="text-[10px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300">
              Activo
            </span>
          </div>
        </div>

        @if ($modalidad === 'linea')
          <div class="mt-2 text-sm">
            <div><span class="font-medium">Fecha de atención:</span> {{ $inv->fecha_atencion ?? '—' }}</div>
            <div class="mt-1">
              <span class="font-medium">URL:</span>
              @if ($inv->url)
                <a class="text-emerald-600 underline" href="{{ $inv->url }}" target="_blank" rel="noopener">Abrir</a>
              @else — @endif
            </div>

            @php $juntosCard = (int)($inv->acudiran_juntos ?? 1) === 1; @endphp
            @if ($juntosCard)
              <div class="mt-1"><span class="font-medium">Horario:</span> {{ $fmtCard($inv->hora_inicio) }} - {{ $fmtCard($inv->hora_fin) }}</div>
            @else
              <div class="mt-1"><span class="font-medium">Horario solicitante:</span> {{ $fmtCard($inv->hora_inicio) }} - {{ $fmtCard($inv->hora_fin) }}</div>
              <div><span class="font-medium">Horario invitado:</span> {{ $fmtCard($inv->hora_inicio_invitado) }} - {{ $fmtCard($inv->hora_fin_invitado) }}</div>
            @endif
          </div>
        @else
          <div class="mt-2 text-sm">
            <span class="font-medium">Fecha de envío:</span> {{ $inv->fecha_envio ?? '—' }}
          </div>
        @endif

        {{-- Controles de asistencia --}}
        <div class="mt-3 text-sm border-t border-neutral-100 dark:border-neutral-800 pt-3">
          <div class="flex items-center justify-between">
            <div>
              <span class="font-medium">¿Asistió?:</span>
              @if (is_null($inv->asistio))
                <span class="text-neutral-500">Sin registrar</span>
              @else
                <span class="{{ $inv->asistio ? 'text-emerald-600' : 'text-red-600' }}">{{ $inv->asistio ? 'Sí' : 'No' }}</span>
              @endif
            </div>

            <div class="flex gap-2">
              @if (is_null($inv->asistio))
                <flux:button size="xs" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, true)">Sí asistió</flux:button>
                <flux:button size="xs" variant="danger" wire:click="marcarAsistencia({{ $inv->id }}, false)">No asistió</flux:button>
              @endif
            </div>
          </div>

          @if (!is_null($inv->acepta_proceso))
            <div class="mt-2">
              <span class="font-medium">Aceptó mediación:</span>
              <span class="{{ $inv->acepta_proceso ? 'text-emerald-600' : 'text-sky-600' }}">
                {{ $inv->acepta_proceso ? 'Sí' : 'No' }}
              </span>
            </div>
          @endif
        </div>
      </div>
    @empty
      <div class="text-sm italic text-neutral-500">Aún no hay invitaciones.</div>
    @endforelse
  </div>
</div>

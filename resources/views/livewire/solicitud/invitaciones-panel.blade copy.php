<div class="space-y-8 max-w-6xl mx-auto">
  @if ($solicitud && $solicitud->tipo_proceso_id != 3)
  @php
  // === Paso 2 dinámico ===
  $etiquetaEtapa2 = $etiquetaEtapa2
  ?? ($solicitud->etapa2_alias
  ?? ($solicitud->tipoProcesoEtapa2->nombre ?? 'Mediación'));

  $steps = [1 => 'Pre-mediación', 2 => $etiquetaEtapa2];
  $current = (int)($tipoProcesoId ?? 0);

  // Base vars
  $isPrimera = $invitaciones->isEmpty();
  $next = (($invitaciones->max('numero_inv') ?? 0) + 1);
  $ultima = $invitaciones->sortByDesc('numero_inv')->first();
  $isPreMediacion = ($current === 1);
  $esEtapa2 = ($current === 2);

  // Etiquetas dinámicas
  $etqUnidadSing = $isPreMediacion ? 'invitación' : 'sesión';
  $etqUnidadPlural = $isPreMediacion ? 'invitaciones' : 'sesiones';

  // Evento/horarios según etapa
  $evEtapa = $esEtapa2 ? ($eventoMediacion ?? null) : ($evento ?? null);
  $fechaAtEvento = $evEtapa->fecha ?? null;
  $opcionSeparados = (int)($evEtapa->opcion_invitacion ?? 1) === 0;

  $fmtHora = function($t) { return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—'; };
  @endphp

  <x-solicitud.section-header :title="$isPreMediacion ? 'Registro de invitaciones' : 'Registro de sesiones'">
    Aquí puedes ver y crear {{ $etqUnidadPlural }}.
  </x-solicitud.section-header>

  @php
  // 2ª de pre-mediación
  $esSegundaPre = $isPreMediacion && $next == 2;

  // Config (máximos)
  $maxInvPre = 2;
  $maxInvEtapa2 = 10;

  // Límites por etapa
  $bloqueoLimitePre = $isPreMediacion && $next > $maxInvPre;
  $bloqueoLimiteEtp2 = $esEtapa2 && $next > $maxInvEtapa2;

  // BLOQUEO DURO: no crear nueva si la última no tiene asistencia registrada
  $bloqueoAsistencia = ($ultima && is_null($ultima->asistio));

  // ---- REGLA CLAVE: la segunda sesión/invitación se hace cuando NO aceptó ----
  // Si asistió y NO hay aún registro de aceptación, primero preguntar (oculta formularios)
  $requiereAceptarAntes = $isPreMediacion && ($ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso));

  // Si ACEPTÓ (=1), ya no se permiten más invitaciones
  $bloqueoPorAcepto = $isPreMediacion && ($ultima && $ultima->asistio === 1 && (int)$ultima->acepta_proceso === 1);

  // La reasignación de la 2ª solo aplica si NO aceptó (==0) y debe existir eventoSegPreMedicion
  $bloqueoReasignacionSegPre = $esSegundaPre
  && ( ($ultima && (int)$ultima->acepta_proceso === 0) ? !$eventoSegPreMedicion : true );

  // Reglas propias de etapa 2
  $bloqueoPorAcuerdo = $esEtapa2 && ($ultima && (int)$ultima->acepta_proceso === 1);

  // Bloqueo final
  $bloqueoNueva = $bloqueoLimitePre || $bloqueoLimiteEtp2 || $bloqueoAsistencia || $requiereAceptarAntes
  || $bloqueoPorAcepto || $bloqueoPorAcuerdo || $bloqueoReasignacionSegPre;

  // Motivo dinámico
  $motivoBloqueo = '';
  if ($bloqueoLimitePre) {
  $motivoBloqueo = "En Pre-mediación solo se permiten {$maxInvPre} invitaciones.";
  } elseif ($bloqueoLimiteEtp2) {
  $motivoBloqueo = "En {$etiquetaEtapa2} solo se permiten {$maxInvEtapa2} sesiones.";
  } elseif ($bloqueoAsistencia) {
  $motivoBloqueo = "Primero registra la asistencia de la última " . ($isPreMediacion ? 'invitación' : 'sesión') . ".";
  } elseif ($requiereAceptarAntes) {
  $motivoBloqueo = 'Confirma si aceptaron mediación antes de crear otra invitación.';
  } elseif ($bloqueoPorAcepto) {
  $motivoBloqueo = 'Ya aceptaron mediación. No se permiten nuevas invitaciones.';
  } elseif ($bloqueoPorAcuerdo) {
  $motivoBloqueo = 'Ya hubo convenio/acuerdo. No se permiten nuevas sesiones.';
  } elseif ($bloqueoReasignacionSegPre) {
  if ($esSegundaPre && $ultima && (int)$ultima->acepta_proceso === 0) {
  $motivoBloqueo = 'Debe existir un evento de reasignación para habilitar la 2ª invitación de Pre-mediación.';
  } else {
  $motivoBloqueo = 'La 2ª invitación solo procede cuando NO aceptó mediación.';
  }
  }

  // CTA
  $accionClick = $isPrimera ? 'primera' : 'nueva';
  $textoBtn = $isPreMediacion
  ? ($isPrimera ? "Enviar invitación" : "Enviar invitación #{$next}")
  : ($isPrimera ? "Crear sesión" : "Crear sesión #{$next}");

  // Chips
  $chipProceso = $isPreMediacion
  ? ['label' => "Pre-mediación • máx. {$maxInvPre}", 'class' => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800
  dark:text-neutral-300']
  : ['label' => "{$etiquetaEtapa2} • máx. {$maxInvEtapa2}", 'class' => 'bg-neutral-100 text-neutral-700
  dark:bg-neutral-800 dark:text-neutral-300'];

  $chipAsistencia = is_null($ultima?->asistio)
  ? ['label' => 'Asistencia: pendiente', 'class' => 'bg-amber-100 text-amber-700 dark:bg-amber-700/20
  dark:text-amber-300']
  : ($ultima->asistio
  ? ['label' => 'Asistencia: Sí', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20
  dark:text-emerald-300']
  : ['label' => 'Asistencia: No', 'class' => 'bg-red-100 text-red-700 dark:bg-red-700/20 dark:text-red-300']);

  $chipAcepto = is_null($ultima?->acepta_proceso)
  ? ['label' => ($isPreMediacion ? 'Aceptación: pendiente' : 'Acuerdo: pendiente'), 'class' => 'bg-amber-100
  text-amber-700 dark:bg-amber-700/20 dark:text-amber-300']
  : ($ultima->acepta_proceso
  ? ['label' => ($isPreMediacion ? 'Aceptó mediación' : 'Con acuerdo'), 'class' => 'bg-emerald-100 text-emerald-700
  dark:bg-emerald-700/20 dark:text-emerald-300']
  : ['label' => ($isPreMediacion ? 'No aceptó mediación' : 'Sin acuerdo'), 'class' => 'bg-sky-100 text-sky-700
  dark:bg-sky-700/20 dark:text-sky-300']);

  // Si el Livewire no nos envía $mostrarPreguntaAceptacion, lo calculamos aquí como fallback
  $mostrarPreguntaAceptacion = $mostrarPreguntaAceptacion
  ?? ($isPreMediacion && $ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso));
  @endphp

  {{-- ====== STEPS ====== --}}
  <ol class="flex items-center gap-3 text-sm">
    @foreach($steps as $id => $label)
    @php $done = $current > $id; $active = $current === $id; @endphp
    <li class="flex items-center gap-2">
      <span
        class="flex h-6 w-6 items-center justify-center rounded-full {{ $done ? 'bg-emerald-600 text-white' : ($active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500') }}">
        {{ $id }}
      </span>
      <span class="{{ $active ? 'font-semibold text-neutral-900 dark:text-white' : 'text-neutral-500' }}">{{ $label
        }}</span>
      @if(!$loop->last) <span class="mx-2 h-px w-8 bg-neutral-200 dark:bg-neutral-700"></span> @endif
    </li>
    @endforeach
  </ol>

  {{-- ====== LAYOUT con sidebar sticky ====== --}}
  <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    {{-- Columna principal --}}
    <div class="md:col-span-8 space-y-6">

      {{-- ========= CARD PRINCIPAL ========= --}}
      <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm"
        x-data="{ openHoras: true }">

        {{-- Resumen compacto --}}
        <div class="mb-4 flex flex-wrap items-center gap-2 text-[11px]">
          <span class="px-2 py-1 rounded-full {{ $chipProceso['class'] }}">{{ $chipProceso['label'] }}</span>
          @if (!$isPrimera)
          <span class="px-2 py-1 rounded-full {{ $chipAsistencia['class'] }}">{{ $chipAsistencia['label'] }}</span>
          @if ($ultima && $ultima->asistio === 1)
          <span class="px-2 py-1 rounded-full {{ $chipAcepto['class'] }}">{{ $chipAcepto['label'] }}</span>
          @endif
          <span class="text-neutral-400">•</span>
          <span class="text-neutral-500">Última: #{{ $ultima->numero_inv }}</span>
          @endif
        </div>

        <div class="flex items-center justify-between mb-2">
          
            
          <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
            @if ($mostrarPreguntaAceptacion)
            Resultado de pre-mediación
            @else
            {{ $isPrimera ? "Crear primera {$etqUnidadSing}" : "Crear nueva {$etqUnidadSing} (#{$next})" }}
            @endif
          </h3>
          
          @if($bloqueoNueva)
          <flux:tooltip content="{{ $motivoBloqueo }}">
            <span
              class="text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300">Bloqueada</span>
          </flux:tooltip>
          @endif
        </div>

        <div class="h-px bg-neutral-100 dark:bg-neutral-800 mb-4"></div>

        {{-- ===== PREGUNTA (solo si asistió y falta aceptar/rechazar) ===== --}}
        @if ($mostrarPreguntaAceptacion)
        @php $textoPregunta = '¿Aceptó mediación?'; @endphp
        <div class="mt-0">
          <div
            class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
            x-data="{ valor: @entangle('aceptoProceso').live }">
            <div class="flex items-center justify-between">
              <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">{{ $textoPregunta }}</p>
              <span class="text-[10px] text-neutral-500">Aplica a #{{ $ultima->numero_inv ?? '—' }}</span>
            </div>

            <div class="mt-2 flex items-center gap-6 text-sm">
              <label class="flex items-center gap-2">
                <input type="radio" wire:model="aceptoProceso" x-model="valor"
                  class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500" value="1">
                <span>Sí</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="radio" wire:model="aceptoProceso" x-model="valor"
                  class="rounded border-neutral-300 text-emerald-600 focus:ring-emerald-500" value="0">
                <span>No</span>
              </label>
            </div>

            {{-- Si marcó NO, pide motivo --}}
            <div class="mt-3" x-show="valor == 0" x-cloak>
              <flux:select wire:model="motivoCancelacion" placeholder="Seleccione un motivo...">
                <flux:select.option value="1">No le interesa la mediación</flux:select.option>
                <flux:select.option value="2">Tenía otro compromiso</flux:select.option>
                <flux:select.option value="3">No confía en el proceso</flux:select.option>
                <flux:select.option value="4">Otro</flux:select.option>
              </flux:select>
              @error('motivoCancelacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- === Si marcó SÍ: subir manifestaciones + generar documentos === --}}
            <div class="mt-3 space-y-4" x-show="valor == 1" x-cloak>
              <p class="text-sm text-neutral-700 dark:text-neutral-300">
                Adjunta las <span class="font-medium">manifestaciones</span> (PDF). Estos archivos se guardarán al
                presionar <span class="font-medium">Confirmar</span>.
              </p>

              {{-- Contenedor a 100% --}}
              <div class="w-full">
                <div class="w-full">
                  {{-- Label y botón en la misma fila --}}
                  <div class="flex items-center justify-between mb-1 w-full">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                      Manifestaciones (PDF) *
                    </label>

                    <a href="{{ route('manifestacion.download', $solicitudId) }}" target="_blank" rel="noopener"
                      class="inline-flex items-center gap-2 rounded-md border border-emerald-600 bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                      Generar documentos
                    </a>
                  </div>

                  {{-- Dropzone ocupa todo el ancho --}}
                  <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10420']" :multiple="true"
                    class="w-full" />

                  @error('manifestaciones')
                  <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                  @enderror

                  <div wire:loading wire:target="manifestaciones" class="mt-1 text-xs text-neutral-500">
                    Subiendo documentos…
                  </div>
                </div>
              </div>
            </div>




            <div class="mt-3">
              <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
            </div>
          </div>
        </div>

        {{-- 🔒 Asistencia pendiente: NO mostrar formularios de nueva agendación --}}
        @elseif ($bloqueoAsistencia)
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Falta registrar asistencia
          </flux:callout.heading>
          <flux:callout.text>
            Primero registra la asistencia de la última {{ $isPreMediacion ? 'invitación' : 'sesión' }} para habilitar
            la nueva {{ $etqUnidadSing }}.
          </flux:callout.text>
        </flux:callout>

        {{-- ===== FORMULARIOS (solo si NO hay pregunta y no bloqueado) ===== --}}
        @elseif(!$bloqueoNueva)

        {{-- Detalles del evento inicial --}}
        @if ($isPrimera && $evEtapa)
        @php $labelFecha = 'Fecha de atención'; @endphp
        <flux:callout color="neutral"
          class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
          <flux:callout.heading class="flex items-center gap-2 text-emerald-700 dark:text-emerald-300">
            <flux:icon.bell variant="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Detalles del evento
          </flux:callout.heading>
          <flux:callout.text class="space-y-3">
            <div class="flex items-center gap-2">
              <flux:icon.calendar class="w-4 h-4 text-emerald-600" />
              <span class="font-medium">{{ $labelFecha }}:</span>
              <span class="font-semibold">{{ $fechaAtEvento ?? '—' }}</span>
            </div>
            @if ($opcionSeparados)
            <div class="mt-2 grid sm:grid-cols-2 gap-3">
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">{{ $fmtHora($evEtapa->hora_inicio ?? $horaInicio) }} - {{
                  $fmtHora($evEtapa->hora_fin ?? $horaFin) }}</p>
              </div>
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">{{ $fmtHora($evEtapa->hora_inicio_invitado ?? $horaInicioInvitado) }} -
                  {{ $fmtHora($evEtapa->hora_fin_invitado ?? $horaFinInvitado) }}</p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">{{ $fmtHora($evEtapa->hora_inicio ?? $horaInicio) }} - {{
                $fmtHora($evEtapa->hora_fin ?? $horaFin) }}</p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @endif

        {{-- ===== BLOQUE ESPECIAL: 2ª Pre - Reasignación (solo si NO aceptó) ===== --}}
        @if ($isPreMediacion && $next == 2 && $ultima && (int)$ultima->acepta_proceso === 0)
        @if ($eventoSegPreMedicion)
        <flux:callout color="neutral"
          class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
          <flux:callout.heading class="flex items-center gap-2 text-emerald-700 dark:text-emerald-300">
            <flux:icon.calendar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Reasignación • Detalles del evento
          </flux:callout.heading>
          <flux:callout.text class="space-y-3">
            <div class="flex items-center gap-2">
              <flux:icon.calendar class="w-4 h-4 text-emerald-600" />
              <span class="font-medium">Fecha:</span>
              <span class="font-semibold">{{ $eventoSegPreMedicion->fecha }}</span>
            </div>
            <div class="flex items-center gap-2">
              <flux:icon.user class="w-4 h-4 text-emerald-600" />
              <span class="font-medium">Facilitador:</span>
              <span class="font-semibold">{{ optional($eventoSegPreMedicion->facilitador)->nombre ?? '—' }}</span>
            </div>
            @if ($opcionSeparados)
            <div class="mt-2 grid sm:grid-cols-2 gap-3">
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">{{ $fmtHora($eventoSegPreMedicion->hora_inicio) }} - {{
                  $fmtHora($eventoSegPreMedicion->hora_fin) }}</p>
              </div>
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">{{ $fmtHora($eventoSegPreMedicion->hora_inicio_invitado) }} - {{
                  $fmtHora($eventoSegPreMedicion->hora_fin_invitado) }}</p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">{{ $fmtHora($eventoSegPreMedicion->hora_inicio) }} - {{
                $fmtHora($eventoSegPreMedicion->hora_fin) }}</p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @endif
        @endif

        {{-- ===== EN LÍNEA / PRESENCIAL (formularios normales) ===== --}}
        @if ($modalidad === 'linea')
        @if ($isPrimera)
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Enlace / Liga" placeholder="https://meet.google.com/..." wire:model="enlaceReunion" />
          @error('enlaceReunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        @else
        <div class="grid md:grid-cols-2 gap-3 max-w-2xl">
          <div>
            <flux:input label="Enlace / Liga (nueva)" placeholder="https://meet.google.com/..."
              wire:model="urlNuevaInv" />
            @error('urlNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>
        @endif
        @else
        @if ($isPrimera)
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Fecha de envío" type="date" wire:model="fechaEnvio" />
          @error('fechaEnvio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mt-4">
          <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
            Horario <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
          </button>
          <div x-show="openHoras" x-collapse x-cloak class=" max-w-lg">
            <flux:select wire:model="horaInicio" placeholder="Hora envio">
              @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
              </flux:select.option> @endforeach
            </flux:select>
          </div>
          @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        @else
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Fecha de envío (nueva)" type="date" wire:model="fechaNuevaInv" />
          @error('fechaNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mt-4">
          <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
            Horario <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
          </button>
          <div x-show="openHoras" x-collapse x-cloak class="max-w-lg">
            <flux:select wire:model="horaInicio" placeholder="Hora Envio">
              @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
              </flux:select.option> @endforeach
            </flux:select>
            {{-- <flux:select wire:model="horaFin" placeholder="Hora fin">
              @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
              </flux:select.option> @endforeach
            </flux:select> --}}
          </div>
          @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          {{-- @error('horaFin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror --}}
        </div>
        @endif
        @endif

        {{-- ===== BLOQUEADO ===== --}}
        @else
        @if ($esSegundaPre)
        @if ($ultima && (int)$ultima->acepta_proceso === 0)
        {{-- Si NO aceptó pero falta crear eventoSegPreMedicion, mostramos el form de reasignación --}}
        <div
          class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
          <h3 class="text-base font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
            <flux:icon.calendar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Reasignación • Fecha y hora del evento
          </h3>

          <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.defer="facilitador" placeholder="Elige facilitador disponible">
              @foreach ($facilitadores as $fac)
              <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
              @endforeach
            </flux:select>

            <x-select-color model="colorEvento" :widthPx="340" />
          </div>

          <flux:input wire:model.defer="fechaNueva" type="date" label="Nueva fecha"
            placeholder="Seleccione la nueva fecha" />

          <div class="pt-2">
            @if (!$opcionSeparados)
            <div class="space-y-2">
              <p class="text-sm font-semibold dark:text-white">Horario (ambas partes)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
              </div>
            </div>
            @else
            <div class="space-y-4">
              <p class="text-sm font-semibold dark:text-white">Horario para solicitante(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioInvitadoEvento" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinInvitadoEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
              </div>
            </div>
            @endif
          </div>

          <div class="pt-2 flex items-center justify-end gap-3">
            <flux:button variant="primary" icon="calendar" class="bg-emerald-600 hover:bg-emerald-700 text-white"
              wire:click="crearEvento" wire:loading.attr="disabled" wire:target="crearEvento">
              Crear evento
            </flux:button>
            <span class="text-xs text-neutral-500" wire:loading wire:target="crearEvento">Creando evento…</span>
          </div>

          <p class="text-xs text-neutral-500">
            Una vez creado el evento de reasignación, este formulario se habilitará automáticamente para crear la
            invitación #2.
          </p>
        </div>
        @else
        {{-- Aceptó o no hay datos: bloquear genérico --}}
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Acción bloqueada
          </flux:callout.heading>
          <flux:callout.text>{{ $motivoBloqueo }}</flux:callout.text>
        </flux:callout>
        @endif
        @else
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Acción bloqueada
          </flux:callout.heading>
          <flux:callout.text>{{ $motivoBloqueo }}</flux:callout.text>
        </flux:callout>
        @endif
        @endif
      </div>
      {{-- ========= /CARD ========= --}}

      {{-- ========= LISTADO: Timeline (colapsable) ========= --}}
      <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold text-neutral-800 dark:text-neutral-100">Historial</h4>
          <div class="text-sm text-neutral-500">Total: {{ $invitaciones->count() }} {{ $etqUnidadPlural }}</div>
        </div>

        @if($invitaciones->isEmpty())
        <p class="text-sm text-neutral-600 dark:text-neutral-300">Aún no hay {{ $etqUnidadPlural }} registradas.</p>
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

          // Si asistió y no hay registro de aceptar/no aceptar, ocultamos detalles
          $bloquearDetalles = $isPreMediacion && ($inv->asistio === 1) && is_null($inv->acepta_proceso);
          @endphp

          <div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
            <span
              class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $dot }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
              <div class="flex items-start justify-between gap-3">
                <div class="text-sm font-semibold">{{ $isPreMediacion ? 'Invitación' : 'Sesión' }} #{{ $inv->numero_inv
                  }}</div>
                <div class="flex items-center gap-2">
                  <span class="text-[10px] px-2 py-1 rounded-full {{ $chipA[1] }}">{{ $chipA[0] }}</span>

                  @if (!is_null($inv->acepta_proceso))
                  <span
                    class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                    {{ $isPreMediacion ? ($inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó') :
                    ($inv->acepta_proceso ? 'Con acuerdo' : 'Sin acuerdo') }}
                  </span>
                  @endif

                  <button type="button"
                    class="text-xs px-2 py-1 rounded-md bg-emerald-600 text-white border border-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 transition inline-flex items-center gap-1"
                    @click="open = !open" :aria-expanded="open.toString()">
                    <span x-show="!open">Ver detalles</span>
                    <span x-show="open" x-cloak>Ocultar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                      <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 15l7-7 7 7" />
                    </svg>
                  </button>
                </div>
              </div>

              <div x-show="open" x-collapse x-cloak>
                @php
                  $evForInv = $inv->evento ?? null;
                  if (!$evForInv) {
                    if ($isPreMediacion) {
                      if ($inv->numero_inv == 1)       $evForInv = $evento ?? null;
                      elseif ($inv->numero_inv == 2)   $evForInv = $eventoSegPreMedicion ?? ($evento ?? null);
                      else                             $evForInv = $evento ?? null;
                    } else {
                      $evForInv = $eventoMediacion ?? null;
                    }
                  }
              
                  $hEvIni     = $evForInv->hora_inicio ?? null;
                  $hEvFin     = $evForInv->hora_fin ?? null;
                  $hEvIniI    = $evForInv->hora_inicio_invitado ?? null;
                  $hEvFinI    = $evForInv->hora_fin_invitado ?? null;
                  $evSeparados = (int)($evForInv->opcion_invitacion ?? 1) === 0;
                @endphp
              
                <div class="mt-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                      {{ $isPreMediacion ? 'Invitación' : 'Sesión' }}
                    </p>
              
                    @if($inv->modalidad === 'linea')
                      <span class="text-xs text-neutral-600 dark:text-neutral-300">
                        Fecha de atención:
                        <span class="font-medium">{{ $inv->fecha_atencion ?: $inv->fecha_envio ?: '—' }}</span>
                      </span>
                    @else
                      <span class="text-xs text-neutral-600 dark:text-neutral-300">
                        Fecha de envío:
                        <span class="font-medium">{{ $inv->fecha_envio ?: $inv->fecha_atencion ?: '—' }}</span>
                      </span>
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
                          <p class="text-[11px] text-neutral-500">
                            {{ $inv->modalidad === 'linea' ? 'Horario (invitación)' : 'Hora envío (capturada)' }}
                          </p>
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
                      <span class="ml-2 px-2 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-700/20 dark:text-amber-300">
                        * Distinto al del evento
                      </span>
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
                        <span class="{{ $inv->asistio ? 'text-emerald-600' : 'text-rose-600' }}">
                          {{ $inv->asistio ? 'Sí' : 'No' }}
                        </span>
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
      {{-- ========= /LISTADO ========= --}}
    </div>

    {{-- Sidebar sticky --}}
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
            @if(!$isPrimera && $ultima)
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
          $tooltipMsg = $mostrarPreguntaAceptacion
          ? 'Primero registra la aceptación / rechazo.'
          : ($bloqueoAsistencia ? 'Primero registra la asistencia.' : $motivoBloqueo);
          @endphp

          @if($bloqueoNueva || $mostrarPreguntaAceptacion || $bloqueoAsistencia)
          <flux:tooltip content="{{ $tooltipMsg }}">
            <div>
              <flux:button class="w-full" disabled variant="primary" icon="lock-closed">{{ $textoBtn }}</flux:button>
            </div>
          </flux:tooltip>
          @else
          <flux:button class="w-full" wire:click="store('{{ $accionClick }}')" variant="primary"
            wire:loading.attr="disabled">{{ $textoBtn }}</flux:button>
          @endif
          <div wire:loading wire:target="store" class="mt-2 text-xs text-neutral-500">Guardando…</div>
        </div>
      </div>
    </aside>
  </div>
  @endif
</div>
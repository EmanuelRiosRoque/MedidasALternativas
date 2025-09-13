  <div class="space-y-8 max-w-6xl mx-auto">
    @if ($solicitud && $solicitud->tipo_proceso_id != 3)
    @php
    // === Paso 2 dinámico ===
    // Si tienes un alias/nombre en BD úsalo aquí, si no, cae a "Mediación"
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
    $esEtapa2 = ($current === 2); // etapa 2 genérica (antes "mediación")

    // Etiquetas dinámicas
    $etqUnidadSing = $isPreMediacion ? 'invitación' : 'sesión';
    $etqUnidadPlural = $isPreMediacion ? 'invitaciones' : 'sesiones';

    // Evento/horarios según etapa (en etapa 2 usa el eventoMediacion si lo tienes)
    $evEtapa = $esEtapa2 ? ($eventoMediacion ?? null) : ($evento ?? null);
    $fechaAtEvento = $evEtapa->fecha ?? null;
    $opcionSeparados = (int)($evEtapa->opcion_invitacion ?? 1) === 0; // 0=separados, 1=juntos
    $fmtHora = function($t) { return $t ? \Carbon\Carbon::parse($t)->format('H:i') : '—'; };
    @endphp

    <x-solicitud.section-header :title="$isPreMediacion ? 'Registro de invitaciones' : 'Registro de sesiones'">
      Aquí puedes ver y crear {{ $etqUnidadPlural }}.
    </x-solicitud.section-header>

    @php
    // 2ª de pre-mediación NO precargar horas (lo gestiona el componente)
    $esSegundaPre = $isPreMediacion && $next == 2;

    // Config (máximos)
    $maxInvPre = 2; $maxInvEtapa2 = 10;

    // Límites por etapa
    $bloqueoLimitePre = $isPreMediacion && $next > $maxInvPre;
    $bloqueoLimiteEtp2 = $esEtapa2 && $next > $maxInvEtapa2;

    // BLOQUEO DURO: no crear nueva si la última no tiene asistencia registrada
    $bloqueoAsistencia = ($ultima && is_null($ultima->asistio));

    // Reglas propias de pre-mediación
    $requiereAceptarAntes = $isPreMediacion && ($ultima && $ultima->asistio === 1 && is_null($ultima->acepta_proceso));
    $bloqueoPorAcepto = $isPreMediacion && ($ultima && $ultima->asistio === 1 && (int)$ultima->acepta_proceso === 1);

    // Reglas propias de etapa 2 (cuando ya hubo acuerdo/convenio)
    $bloqueoPorAcuerdo = $esEtapa2 && ($ultima && (int)$ultima->acepta_proceso === 1);

    // Bloqueo final del CTA
    $bloqueoNueva = $bloqueoLimitePre || $bloqueoLimiteEtp2 || $bloqueoAsistencia || $requiereAceptarAntes ||
    $bloqueoPorAcepto || $bloqueoPorAcuerdo;

    // Motivo (dinámico)
    $motivoBloqueo = '';
    if ($bloqueoLimitePre) $motivoBloqueo = "En Pre-mediación solo se permiten {$maxInvPre} invitaciones.";
    elseif ($bloqueoLimiteEtp2) $motivoBloqueo = "En {$etiquetaEtapa2} solo se permiten {$maxInvEtapa2} sesiones.";
    elseif ($bloqueoAsistencia) $motivoBloqueo = "Primero registra la asistencia de la última " . ($isPreMediacion ?
    'invitación' : 'sesión') . ".";
    elseif ($requiereAceptarAntes) $motivoBloqueo = 'Confirma si aceptaron mediación antes de crear otra invitación.';
    elseif ($bloqueoPorAcepto) $motivoBloqueo = 'Ya aceptaron mediación. No se permiten nuevas invitaciones.';
    elseif ($bloqueoPorAcuerdo) $motivoBloqueo = 'Ya hubo convenio/acuerdo. No se permiten nuevas sesiones.';

    // CTA
    $accionClick = $isPrimera ? 'primera' : 'nueva';
    $textoBtn = $isPreMediacion
    ? ($isPrimera ? "Crear invitación" : "Crear invitación #{$next}")
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
    @endphp

    <ol class="flex items-center gap-3 text-sm">
      @foreach($steps as $id => $label)
      @php $done = $current > $id; $active = $current === $id; @endphp
      <li class="flex items-center gap-2">
        <span
          class="flex h-6 w-6 items-center justify-center rounded-full
              {{ $done ? 'bg-emerald-600 text-white' : ($active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500') }}">
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

        {{-- ========= FORMULARIO ========= --}}
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
              {{ $isPrimera ? "Crear primera {$etqUnidadSing}" : "Crear nueva {$etqUnidadSing} (#{$next})" }}
            </h3>
            @if($bloqueoNueva)
            <flux:tooltip content="{{ $motivoBloqueo }}">
              <span
                class="text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300">
                Bloqueada
              </span>
            </flux:tooltip>
            @endif
          </div>
          <div class="h-px bg-neutral-100 dark:bg-neutral-800 mb-4"></div>

          {{-- ====== EN LÍNEA / PRESENCIAL ====== --}}
          @if ($modalidad === 'linea')
          {{-- PRIMERA EN LÍNEA --}}
          @if ($isPrimera)
          @if ($evEtapa)
          <flux:callout icon="computer-desktop" color="neutral" class="mb-4">
            <flux:callout.heading>Detalles del evento</flux:callout.heading>
            <flux:callout.text class="space-y-1">
              <div><span class="font-medium">Fecha de atención:</span> {{ $fechaAtEvento ?? '—' }}</div>
              @if ($opcionSeparados)
              <div class="mt-2 grid sm:grid-cols-2 gap-3">
                <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                  <p class="text-xs text-neutral-500">Solicitante(s)</p>
                  <p class="text-sm font-medium">{{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}</p>
                </div>
                <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                  <p class="text-xs text-neutral-500">Invitado(s)</p>
                  <p class="text-sm font-medium">{{ $fmtHora($horaInicioInvitado) }} - {{ $fmtHora($horaFinInvitado) }}
                  </p>
                </div>
              </div>
              @else
              <div class="mt-2 rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                <p class="text-xs text-neutral-500">Horario (evento)</p>
                <p class="text-sm font-medium">{{ $fmtHora($horaInicio) }} - {{ $fmtHora($horaFin) }}</p>
              </div>
              @endif
            </flux:callout.text>
          </flux:callout>
          @endif

          <div class="grid gap-3 max-w-lg">
            <flux:input label="Enlace / Liga" placeholder="https://meet.google.com/..." wire:model="enlaceReunion" />
            @error('enlaceReunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>

          @unless ($isPreMediacion)
          {{-- En etapa 2, la 1ª sí pide horarios --}}
          <div class="mt-4">
            <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
              Horarios
              <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
            </button>
            <div x-show="openHoras" x-collapse x-cloak class="mt-2">
              @if ($opcionSeparados)
              <div class="grid sm:grid-cols-2 gap-3">
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                  <p class="text-sm font-semibold dark:text-white mb-2">Solicitante(s)</p>
                  <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                    <flux:select wire:model="horaFin" placeholder="Hora fin">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                  </div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                  <p class="text-sm font-semibold dark:text-white mb-2">Invitado(s)</p>
                  <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model="horaInicioInvitado" placeholder="Hora inicio (invitado)">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                    <flux:select wire:model="horaFinInvitado" placeholder="Hora fin (invitado)">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                  </div>
                </div>
              </div>
              @else
              <div class="grid grid-cols-2 gap-2 max-w-2xl">
                <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model="horaFin" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
              </div>
              @endif
            </div>
          </div>
          @endunless

          {{-- N-ÉSIMAS EN LÍNEA --}}
          @else
          <div class="grid md:grid-cols-2 gap-3 max-w-2xl">
            <div>
              <flux:input label="Enlace / Liga (nueva)" placeholder="https://meet.google.com/..."
                wire:model="urlNuevaInv" />
              @error('urlNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
              <flux:input label="Fecha de atención (nueva)" type="date" wire:model="fechaNuevaInv" />
              @error('fechaNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          {{-- Horarios: en 2ª de pre NO se precargan (component), pero se muestran 2 bloques si van separados --}}
          <div class="mt-4">
            <div class="flex items-center gap-2 text-xs">
              @if($esSegundaPre)
              <span
                class="px-2 py-1 rounded-full bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300">
                En la 2ª invitación de Pre-mediación no se precargan horas.
              </span>
              @endif
            </div>

            <button type="button" class="mt-2 text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
              Horarios
              <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
            </button>
            <div x-show="openHoras" x-collapse x-cloak class="mt-2">
              @if ($opcionSeparados)
              <div class="grid sm:grid-cols-2 gap-3">
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                  <p class="text-sm font-semibold dark:text-white mb-2">Solicitante(s)</p>
                  <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                    <flux:select wire:model="horaFin" placeholder="Hora fin">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                  </div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                  <p class="text-sm font-semibold dark:text-white mb-2">Invitado(s)</p>
                  <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model="horaInicioInvitado" placeholder="Hora inicio">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                    <flux:select wire:model="horaFinInvitado" placeholder="Hora fin">
                      @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                      </flux:select.option> @endforeach
                    </flux:select>
                  </div>
                </div>
              </div>
              @else
              <div class="grid grid-cols-2 gap-2 max-w-2xl">
                <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
                <flux:select wire:model="horaFin" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                  </flux:select.option> @endforeach
                </flux:select>
              </div>
              @endif
            </div>
          </div>
          @endif

          @else
          {{-- ====== PRESENCIAL ====== --}}
          @if ($isPrimera)
          <div class="grid gap-3 max-w-lg">
            <flux:input label="Fecha de envío" type="date" wire:model="fechaEnvio" />
            @error('fechaEnvio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>

          <div class="mt-4">
            <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
              Horarios
              <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
            </button>
            <div x-show="openHoras" x-collapse x-cloak class="mt-2 grid grid-cols-2 gap-2 max-w-lg">
              <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                </flux:select.option> @endforeach
              </flux:select>
              <flux:select wire:model="horaFin" placeholder="Hora fin">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                </flux:select.option> @endforeach
              </flux:select>
            </div>
            @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            @error('horaFin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
          @else
          <div class="grid gap-3 max-w-lg">
            <flux:input label="Fecha de envío (nueva)" type="date" wire:model="fechaNuevaInv" />
            @error('fechaNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>

          <div class="mt-4">
            <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
              Horarios
              <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
            </button>
            <div x-show="openHoras" x-collapse x-cloak class="mt-2 grid grid-cols-2 gap-2 max-w-lg">
              <flux:select wire:model="horaInicio" placeholder="Hora inicio">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                </flux:select.option> @endforeach
              </flux:select>
              <flux:select wire:model="horaFin" placeholder="Hora fin">
                @foreach ($horarios as $valor => $etiqueta) <flux:select.option value="{{ $valor }}">{{ $etiqueta }}
                </flux:select.option> @endforeach
              </flux:select>
            </div>
            @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            @error('horaFin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
          @endif
          @endif

          {{-- ====== Pregunta de resultado (si aplica) ====== --}}
          @php
          $textoPregunta = $isPreMediacion
          ? '¿Aceptó mediación?'
          : '¿Se llegó a un convenio/acuerdo?';
          @endphp

          @if ($mostrarPreguntaAceptacion)
          <div class="mt-6">
            <div
              class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
              x-data="{ valor: @entangle('aceptoProceso').live }">
              <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                  {{ $textoPregunta }}
                </p>
                <span class="text-[10px] text-neutral-500">
                  Aplica a #{{ $ultima->numero_inv ?? '—' }}
                </span>
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

              {{-- === SOLO EN PRE-MEDIACIÓN, cuando responde NO (motivo) === --}}
              @if ($isPreMediacion)
              <div class="mt-3" x-show="valor == 0" x-cloak>
                <flux:select wire:model="motivoCancelacion" placeholder="Seleccione un motivo...">
                  <flux:select.option value="1">No le interesa la mediación</flux:select.option>
                  <flux:select.option value="2">Tenía otro compromiso</flux:select.option>
                  <flux:select.option value="3">No confía en el proceso</flux:select.option>
                  <flux:select.option value="4">Otro</flux:select.option>
                </flux:select>
                @error('motivoCancelacion')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
              @endif

              {{-- === SOLO EN PRE-MEDIACIÓN, cuando responde SÍ (manifestaciones) === --}}
              @if ($isPreMediacion)
              <div class="mt-3 space-y-2" x-show="valor == 1" x-cloak>
                <div class="flex items-center justify-between gap-3">
                  <p class="text-sm font-semibold dark:text-white">
                    Documentos de convenio/manifestaciones
                  </p>
                  <div class="flex items-center gap-2">
                 

                    <flux:button as="a" size="sm" variant="primary" icon="arrow-down-tray"
                      href="{{ route('manifestacion.download', $solicitud->id) }}">
                      Descargar manifestaciones
                    </flux:button>
                   
                  </div>
                </div>

                <p class="text-xs text-neutral-500">
                  Sube los convenios/manifestaciones firmadas en PDF (puedes subir varios archivos, máx. 10&nbsp;MB c/u).
                </p>

                {{-- Dropzone (múltiple) --}}
                <div class="w-full max-w-full">
                  <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10240']" :multiple="true"
                    key="convenios-{{ $ultima->id ?? 'nuevo' }}" />
                </div>

                @error('manifestaciones')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @error('manifestaciones.*')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
              </div>
              @endif

              <div class="mt-3">
                <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">
                  Confirmar
                </flux:button>
              </div>
            </div>
          </div>
          @endif

        </div>

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
            : ($inv->asistio ? ['Asistió','bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200']
            : ['No asistió','bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200']);
            $dot = is_null($inv->asistio) ? 'bg-amber-400' : ($inv->asistio ? 'bg-emerald-500' : 'bg-rose-500');
            @endphp

            <div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
              <span
                class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $dot }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

              <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
                {{-- Encabezado + chips + toggle --}}
                <div class="flex items-start justify-between gap-3">
                  <div class="text-sm font-semibold">
                    {{ $isPreMediacion ? 'Invitación' : 'Sesión' }} #{{ $inv->numero_inv }}
                  </div>

                  <div class="flex items-center gap-2">
                    <span class="text-[10px] px-2 py-1 rounded-full {{ $chipA[1] }}">{{ $chipA[0] }}</span>
                    @if (!is_null($inv->acepta_proceso))
                    <span
                      class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                      {{ $isPreMediacion ? ($inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó') :
                      ($inv->acepta_proceso ? 'Con acuerdo' : 'Sin acuerdo') }}
                    </span>
                    @endif

                    {{-- Botón toggle --}}
                    <button type="button"
                      class="text-xs px-2 py-1 rounded-md border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-neutral-800 transition inline-flex items-center gap-1"
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

                {{-- Contenido colapsable --}}
                <div x-show="open" x-collapse x-cloak>
                  <div class="mt-3 text-sm">
                    @if ($inv->modalidad === 'linea')
                    <div><span class="font-medium">Fecha de atención:</span> {{ $fecha ?? '—' }}</div>
                    <div class="mt-1">
                      <span class="font-medium">URL:</span>
                      @if ($inv->url)
                      <a class="text-emerald-600 underline" href="{{ $inv->url }}" target="_blank"
                        rel="noopener">Abrir</a>
                      @else — @endif
                    </div>
                    @php $juntosCard = (int)($inv->acudiran_juntos ?? 1) === 1; @endphp
                    @if ($juntosCard)
                    <div class="mt-2 rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-xs text-neutral-500">Horario</p>
                      <p class="text-sm font-medium">{{ $fmtHora($inv->hora_inicio) }} - {{ $fmtHora($inv->hora_fin) }}
                      </p>
                    </div>
                    @else
                    <div class="mt-2 grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-xs text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">{{ $fmtHora($inv->hora_inicio) }} - {{ $fmtHora($inv->hora_fin) }}
                        </p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-xs text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">{{ $fmtHora($inv->hora_inicio_invitado) }} - {{
                          $fmtHora($inv->hora_fin_invitado) }}</p>
                      </div>
                    </div>
                    @endif
                    @else
                    <div><span class="font-medium">Fecha de envío:</span> {{ $fecha ?? '—' }}</div>
                    @if ($inv->hora_inicio && $inv->hora_fin)
                    <div class="mt-2 rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-xs text-neutral-500">Horario</p>
                      <p class="text-sm font-medium">{{ $fmtHora($inv->hora_inicio) }} - {{ $fmtHora($inv->hora_fin) }}
                      </p>
                    </div>
                    @endif
                    @endif
                  </div>

                  {{-- Controles de asistencia --}}
                  <div class="mt-3 text-sm border-t border-neutral-100 dark:border-neutral-800 pt-3">
                    <div class="flex items-center justify-between">
                      <div>
                        <span class="font-medium">¿Asistió?:</span>
                        @if (is_null($inv->asistio))
                        <span class="text-neutral-500">Sin registrar</span>
                        @else
                        <span class="{{ $inv->asistio ? 'text-emerald-600' : 'text-rose-600' }}">{{ $inv->asistio ? 'Sí' :
                          'No' }}</span>
                        @endif
                      </div>
                      <div class="flex gap-2">
                        @if (is_null($inv->asistio))
                        <flux:button size="xs" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, true)">Sí
                          asistió</flux:button>
                        <flux:button size="xs" variant="danger" wire:click="marcarAsistencia({{ $inv->id }}, false)">No
                          asistió</flux:button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div> {{-- /colapsable --}}
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>

      {{-- Sidebar sticky --}}
      <aside class="md:col-span-4">
        <div class="md:sticky md:top-4 md:self-start space-y-4">
          {{-- Resumen --}}
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

          {{-- CTA persistente (deshabilitado cuando $bloqueoNueva=true) --}}
          <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
            @if($bloqueoNueva)
            <flux:tooltip content="{{ $motivoBloqueo }}">
              <div>
                <flux:button class="w-full" disabled variant="primary" icon="lock-closed">
                  {{ $textoBtn }}
                </flux:button>
              </div>
            </flux:tooltip>
            @else
            <flux:button class="w-full" wire:click="store('{{ $accionClick }}')" variant="primary"
              wire:loading.attr="disabled">
              {{ $textoBtn }}
            </flux:button>
            @endif

            <div wire:loading wire:target="store" class="mt-2 text-xs text-neutral-500">Guardando…</div>
          </div>
        </div>
      </aside>
    </div>
    @endif
  </div>
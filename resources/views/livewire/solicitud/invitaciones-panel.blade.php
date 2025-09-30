<div class="space-y-8 max-w-6xl mx-auto">
  @if ($solicitud && $solicitud->tipo_proceso_id != 3)

  <x-solicitud.section-header title="Registro de invitaciones">
    Aquí puedes ver y crear {{ $etqUnidadPlural }}.
  </x-solicitud.section-header>

  {{-- ====== STEPS (solo Pre-mediación) ====== --}}
  <ol class="flex items-center gap-3 text-sm">
    @foreach($steps as $id => $label)
    <li class="flex items-center gap-2">
      <span
        class="flex h-6 w-6 items-center justify-center rounded-full
          {{ ($current > $id) ? 'bg-emerald-600 text-white' : (($current === $id) ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500') }}">
        {{ $id }}
      </span>
      <span class="{{ $current === $id ? 'font-semibold text-neutral-900 dark:text-white' : 'text-neutral-500' }}">{{
        $label }}</span>
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
          @php($__chipA = $chipAsistencia) {{-- solo para evitar recalcular en vista --}}
          <span class="px-2 py-1 rounded-full {{ $__chipA['class'] }}">{{ $__chipA['label'] }}</span>

          @if ($ultima && $ultima->asistio === 1)
          @php($__chipC = $chipAcepto)
          <span class="px-2 py-1 rounded-full {{ $__chipC['class'] }}">{{ $__chipC['label'] }}</span>
          @endif

          <span class="text-neutral-400">•</span>
          <span class="text-neutral-500">Última: #{{ $ultima->numero_inv }}</span>
          @endif
        </div>

        <div class="flex items-center justify-between mb-2">
          <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
            @if ($hayCancelacion)
                Registro cancelado
            @elseif ($mostrarPreguntaAceptacionVista)
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
        @if ($mostrarPreguntaAceptacionVista)
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

            <div class="mt-3 space-y-4" x-show="valor == 0" x-cloak>
              <flux:select wire:model="motivoCancelacion" placeholder="Seleccione un motivo...">
                @foreach ($motivosCierre as $motivos)
                <flux:select.option value="{{ $motivos->id }}">{{ $motivos->motivo }}</flux:select.option>
                @endforeach
              </flux:select>
              @error('motivoCancelacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

              <flux:textarea label="Notas u Observaciones" wire:model='notas_observaciones' />
            </div>

            <div class="mt-3 space-y-4" x-show="valor == 1" x-cloak>
              <div>
                <a href="{{ route('seguimiento.download', ['fecha' => $evento->fecha ?? date('Y-m-d')]) }}">
                  <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                    Descargar seguimiento
                  </flux:button>
                </a>
              </div>

              <div
                class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 space-y-3 shadow-sm">
                <div class="flex items-center justify-between">
                  <label class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Manifestaciones (PDF) <span class="text-red-500">*</span>
                  </label>

                  <a href="{{ route('manifestacion.download', $solicitudId) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 rounded-md border border-emerald-600 bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Generar
                  </a>
                </div>

                <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10420']" :multiple="true"
                  class="w-full" />
                @error('manifestaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="manifestaciones" class="text-xs text-neutral-500">
                  Subiendo documentos…
                </div>
              </div>

              <flux:textarea label="Notas u Observaciones" wire:model="notas_observaciones" />
            </div>

            <div class="mt-3">
              <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
            </div>
          </div>
        </div>

        {{-- Asistencia pendiente --}}
        @elseif ($bloqueoAsistencia)
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Falta registrar asistencia
          </flux:callout.heading>
          <flux:callout.text>
            Primero registra la asistencia de la última invitación para habilitar la nueva invitación.
          </flux:callout.text>
        </flux:callout>

        {{-- ===== FORMULARIOS (si no hay pregunta y no bloqueado) ===== --}}
        @elseif(!$bloqueoNueva)

        {{-- Detalles del evento inicial (pre-mediación) --}}
        @if ($isPrimera && $evEtapa)
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
                class="rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">{{ $this->formatHora($evEtapa->hora_inicio ?? $horaInicio) }} - {{
                  $this->formatHora($evEtapa->hora_fin ?? $horaFin) }}</p>
              </div>
              <div
                class="rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">{{ $this->formatHora($evEtapa->hora_inicio_invitado ??
                  $horaInicioInvitado) }} - {{ $this->formatHora($evEtapa->hora_fin_invitado ?? $horaFinInvitado) }}</p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">{{ $this->formatHora($evEtapa->hora_inicio ?? $horaInicio) }} - {{
                $this->formatHora($evEtapa->hora_fin ?? $horaFin) }}</p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @endif

        {{-- ===== BLOQUE ESPECIAL: 2ª Pre - Reasignación (si NO asistió en la #1) ===== --}}
        @if ($next == 2 && $ultima && (int)$ultima->numero_inv === 1 && (int)$ultima->asistio === 0)
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
                class="rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">{{ $this->formatHora($eventoSegPreMedicion->hora_inicio) }} - {{
                  $this->formatHora($eventoSegPreMedicion->hora_fin) }}</p>
              </div>
              <div
                class="rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">{{ $this->formatHora($eventoSegPreMedicion->hora_inicio_invitado) }} -
                  {{ $this->formatHora($eventoSegPreMedicion->hora_fin_invitado) }}</p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-neutral-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">{{ $this->formatHora($eventoSegPreMedicion->hora_inicio) }} - {{
                $this->formatHora($eventoSegPreMedicion->hora_fin) }}</p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @endif
        @endif

        {{-- ===== Formularios por modalidad ===== --}}
        @if ($modalidad == 2)
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
          <div x-show="openHoras" x-collapse x-cloak class="max-w-lg">
            <flux:select wire:model="horaInicio" placeholder="Hora envio">
              @foreach ($horarios as $valor => $etiqueta)
              <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
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
              @foreach ($horarios as $valor => $etiqueta)
              <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>
          @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        @endif
        @endif

        {{-- ===== BLOQUEADO ===== --}}
        @else
        @if ($esSegundaPre)
        @if ($ultima && (int)$ultima->numero_inv === 1 && (int)$ultima->asistio === 0)
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
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>
            </div>
            @else
            <div class="space-y-4">
              <p class="text-sm font-semibold dark:text-white">Horario para solicitante(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioInvitadoEvento" placeholder="Hora inicio">
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinInvitadoEvento" placeholder="Hora fin">
                  @foreach ($horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
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
          @php($__evForInv = $this->evForInv($inv))
          <div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
            <span
              class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $this->dotClass($inv) }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
              <div class="flex items-start justify-between gap-3">
                <div class="text-sm font-semibold">Invitación #{{ $inv->numero_inv }}</div>
                <div class="flex items-center gap-2">
                  @php($__chipRow = $this->chipAsistenciaRow($inv))
                  <span class="text-[10px] px-2 py-1 rounded-full {{ $__chipRow[1] }}">{{ $__chipRow[0] }}</span>

                  @if (!is_null($inv->acepta_proceso))
                  <span
                    class="text-[10px] px-2 py-1 rounded-full {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300' : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                    {{ $inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó' }}
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
                <div
                  class="mt-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">Invitación</p>

                    @if($inv->modalidad == 2)
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

                  @if ($inv->modalidad == 2)
                  <div class="mt-2 text-sm">
                    <span class="font-medium">URL:</span>
                    @if ($inv->url)
                    <a class="text-emerald-600 underline" href="{{ $inv->url }}" target="_blank"
                      rel="noopener">Abrir</a>
                    @else
                    <span class="text-neutral-500">—</span>
                    @endif
                  </div>
                  @endif

                  <div class="mt-2 text-sm">
                    @if ((int)($inv->acudiran_juntos ?? 1) === 1)
                    @if ($inv->hora_inicio || $inv->hora_fin || $modalidad == 1)
                    <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-[11px] text-neutral-500">{{ $inv->modalidad == 2 ? 'Horario (invitación)' :
                        'Hora envío (capturada)' }}</p>
                      <p class="text-sm font-medium">
                        {{ $inv->hora_inicio ? $this->formatHora($inv->hora_inicio) : '—' }}
                        @if($inv->hora_fin) - {{ $this->formatHora($inv->hora_fin) }} @endif
                      </p>
                    </div>
                    @elseif($modalidad == 2)
                    @if (!$this->isSeparados($__evForInv))
                    @if (($__evForInv->hora_inicio ?? null) && ($__evForInv->hora_fin ?? null))
                    <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-[11px] text-neutral-500">Horario (evento)</p>
                      <p class="text-sm font-medium">{{ $this->formatHora($__evForInv->hora_inicio) }} - {{
                        $this->formatHora($__evForInv->hora_fin) }}</p>
                    </div>
                    @else
                    <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
                    @endif
                    @else
                    <div class="grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">{{ ($__evForInv->hora_inicio ?? null) ?
                          $this->formatHora($__evForInv->hora_inicio) : '—' }} - {{ ($__evForInv->hora_fin ?? null) ?
                          $this->formatHora($__evForInv->hora_fin) : '—' }}</p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">{{ ($__evForInv->hora_inicio_invitado ?? null) ?
                          $this->formatHora($__evForInv->hora_inicio_invitado) : '—' }} - {{
                          ($__evForInv->hora_fin_invitado ?? null) ? $this->formatHora($__evForInv->hora_fin_invitado) :
                          '—' }}</p>
                      </div>
                    </div>
                    @endif
                    @endif
                    @else
                    <div class="grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">{{ $inv->hora_inicio ? $this->formatHora($inv->hora_inicio) : '—'
                          }} - {{ $inv->hora_fin ? $this->formatHora($inv->hora_fin) : '—' }}</p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">{{ $inv->hora_inicio_invitado ?
                          $this->formatHora($inv->hora_inicio_invitado) : '—' }} - {{ $inv->hora_fin_invitado ?
                          $this->formatHora($inv->hora_fin_invitado) : '—' }}</p>
                      </div>
                    </div>
                    @endif
                  </div>

                  <div class="mt-2 text-xs">
                    <span
                      class="px-2 py-1 rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                      Facilitador (invitación): {{ optional($inv->facilitador)->nombre ?? '—' }}
                    </span>
                    @if ($__evForInv && $inv->facilitador && optional($__evForInv->facilitador)->nombre !==
                    optional($inv->facilitador)->nombre)
                    <span
                      class="ml-2 px-2 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-700/20 dark:text-amber-300">
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
                      <flux:button size="xs" variant="primary" wire:click="marcarAsistencia({{ $inv->id }}, true)">Sí
                        asistió</flux:button>
                      <flux:button size="xs" variant="danger" wire:click="marcarAsistencia({{ $inv->id }}, false)">No
                        asistió</flux:button>
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
      <div class="md:sticky md:top-4 md:self-start">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
          <div class="text-sm font-semibold mb-2">Resumen</div>
          <dl class="text-sm space-y-2">
            <div class="flex justify-between">
              <dt class="text-neutral-500">Etapa</dt>
              <dd class="font-medium">Pre-mediación</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Siguiente #</dt>
              <dd class="font-medium">#{{ $next }}</dd>
            </div>
            @if(!$isPrimera && $ultima)
            <div class="flex justify-between">
              <dt class="text-neutral-500">Última invitación</dt>
              <dd>#{{ $ultima->numero_inv }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Asistencia</dt>
              <dd>{{ is_null($ultima->asistio) ? 'Pendiente' : ($ultima->asistio ? 'Sí' : 'No') }}</dd>
            </div>
            @endif
          </dl>
        </div>

        <div
          class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900 space-y-2">
          @if($bloqueoNueva || $mostrarPreguntaAceptacionVista || $bloqueoAsistencia)
          <flux:tooltip content="{{ $tooltipMsg }}">
            <div>
              <flux:button class="w-full" disabled variant="primary" icon="lock-closed">{{ $textoBtn }}</flux:button>
            </div>
          </flux:tooltip>
          @else
          <flux:button class="w-full" wire:click="store('{{ $accionClick }}')" variant="primary"
            wire:loading.attr="disabled">{{ $textoBtn }}</flux:button>
          @endif

          <div>
            @if (
              is_null($solicitud->tipo_cancelacion_id)
              && (! $ultima || (int)$ultima->acepta_proceso !== 1)
          )
            <flux:modal.trigger name="cancelar-registro">
              <flux:button variant="danger" class=" w-full">
                Cancelar Registro
              </flux:button>
            </flux:modal.trigger>
          @endif
          </div>

          <flux:modal name="cancelar-registro" class="min-w-[22rem]">
            <div class="space-y-6">
              <div>
                <flux:heading size="lg">Cancelar registro</flux:heading>

                <flux:text class="mt-2">
                  <p>Está a punto de cancelar este registro.</p>
                  <p>Esta acción no se puede deshacer y se registrará el motivo de cancelación.</p>
                </flux:text>
              </div>

              <flux:select wire:model="motivoCancelacion" placeholder="Seleccione un motivo...">
                @foreach ($motivosCierre as $motivos)
                <flux:select.option value="{{ $motivos->id }}">{{ $motivos->motivo }}</flux:select.option>
                @endforeach
              </flux:select>
              @error('motivoCancelacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

              <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                  <flux:button variant="ghost">Regresar</flux:button>
                </flux:modal.close>

                <flux:button wire:click='cancelarRegistro' variant="danger">Cancelar</flux:button>
              </div>
            </div>
          </flux:modal>

          <div wire:loading wire:target="store" class="mt-2 text-xs text-neutral-500">Guardando…</div>
        </div>
      </div>
    </aside>
  </div>
  @endif
</div>
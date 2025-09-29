<div class="space-y-8 max-w-6xl mx-auto">
  @if ($this->solicitud && $this->solicitud->tipo_proceso_id != 3)

  <x-solicitud.section-header :title="$this->isPreMediacion ? 'Registro de invitaciones' : 'Registro de sesiones'">
    Aquí puedes ver y crear {{ $this->etqUnidadPlural }}.
  </x-solicitud.section-header>

  {{-- ====== STEPS ====== --}}
  <ol class="flex items-center gap-3 text-sm">
    @foreach($this->steps as $id => $label)
    @php($done = $this->current > $id) {{-- ← si no quieres ni esta línea, ver nota al final --}}
    @php($active = $this->current === $id)
    <li class="flex items-center gap-2">
      <span
        class="flex h-6 w-6 items-center justify-center rounded-full
            {{ $done ? 'bg-emerald-600 text-white' : ($active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-100 text-neutral-500') }}">
        {{ $id }}
      </span>
      <span class="{{ $active ? 'font-semibold text-neutral-900 dark:text-white' : 'text-neutral-500' }}">
        {{ $label }}
      </span>
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
          <span class="px-2 py-1 rounded-full {{ $this->chipProceso['class'] }}">{{ $this->chipProceso['label']
            }}</span>

          @if ($this->chipAsistencia)
          <span class="px-2 py-1 rounded-full {{ $this->chipAsistencia['class'] }}">{{ $this->chipAsistencia['label']
            }}</span>
          @endif

          @if ($this->chipAcepto)
          <span class="px-2 py-1 rounded-full {{ $this->chipAcepto['class'] }}">{{ $this->chipAcepto['label'] }}</span>
          @endif

          @if(!$this->isPrimera && $this->ultima)
          <span class="text-neutral-400">•</span>
          <span class="text-neutral-500">Última: #{{ $this->ultima->numero_inv }}</span>
          @endif
        </div>

        <div class="flex items-center justify-between mb-2">
          @if ($this->tituloHeader)
          <h3 class="text-base font-semibold text-neutral-800 dark:text-neutral-100">
            {{ $this->tituloHeader }}
          </h3>
          @endif

          @if($this->bloqueoNueva)
          <flux:tooltip content="{{ $this->motivoBloqueo }}">
            <span
              class="text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300">
              Bloqueada
            </span>
          </flux:tooltip>
          @endif
        </div>

        <div class="h-px bg-neutral-100 dark:bg-neutral-800 mb-4"></div>

        {{-- ===== PREGUNTA (solo si asistió y falta aceptar/rechazar) ===== --}}
        @if ($this->mostrarPreguntaAceptacion)
        @if ($this->isPreMediacion)
        <div class="mt-0">
          <div
            class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
            x-data="{ valor: @entangle('aceptoProceso').live }">

            <div class="flex items-center justify-between">
              <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">¿Aceptó mediación?</p>
              <span class="text-[10px] text-neutral-500">Aplica a #{{ $this->ultima->numero_inv ?? '—' }}</span>
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

            {{-- Si marcó SÍ: subir manifestaciones + generar documentos --}}
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
                  <a href="{{ route('manifestacion.download', $this->solicitudId) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 rounded-md border border-emerald-600 bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Generar documentos
                  </a>
                </div>

                <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10420']" :multiple="true"
                  class="w-full" />
                @error('manifestaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                <div wire:loading wire:target="manifestaciones" class="mt-1 text-xs text-neutral-500">Subiendo
                  documentos…</div>
              </div>
            </div>

            <div class="mt-3">
              <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
            </div>
          </div>
        </div>

        {{-- MEDIACIÓN: ¿Se llegó a un convenio/acuerdo? --}}
        @else
        <div
          class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
          x-data="{ valor: @entangle('aceptoProceso').live }">

          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">¿Se llegó a un convenio/acuerdo?</p>
            <span class="text-[10px] text-neutral-500">Aplica a #{{ $this->ultima->numero_inv ?? '—' }}</span>
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

          <div class="mt-3">
            <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
          </div>
        </div>
        @endif
        {{-- Asistencia pendiente: NO mostrar formularios de nueva agendación --}}
        @elseif ($this->bloqueoAsistencia)
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Falta registrar asistencia
          </flux:callout.heading>
          <flux:callout.text>
            Primero registra la asistencia de la última {{ $this->isPreMediacion ? 'invitación' : 'sesión' }} para
            habilitar
            la nueva {{ $this->etqUnidadSing }}.
          </flux:callout.text>
        </flux:callout>

        {{-- ===== FORMULARIOS (solo si NO hay pregunta y no bloqueado) ===== --}}
        @elseif(!$this->bloqueoNueva)

        {{-- Detalles del evento inicial --}}
        @if ($this->isPrimera && $this->evEtapa)
        <flux:callout color="neutral"
          class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
          <flux:callout.heading class="flex items-center gap-2 text-emerald-700 dark:text-emerald-300">
            <flux:icon.bell variant="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Detalles del evento
          </flux:callout.heading>
          <flux:callout.text class="space-y-3">
            <div class="flex items-center gap-2">
              <flux:icon.calendar class="w-4 h-4 text-emerald-600" />
              <span class="font-medium">Fecha de atención:</span>
              <span class="font-semibold">{{ $this->fechaAtEvento ?? '—' }}</span>
            </div>

            @if ($this->opcionSeparados)
            <div class="mt-2 grid sm:grid-cols-2 gap-3">
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">
                  {{ $this->fmtHora(optional($this->evEtapa)->hora_inicio ?? $this->horaInicio) }}
                  -
                  {{ $this->fmtHora(optional($this->evEtapa)->hora_fin ?? $this->horaFin) }}
                </p>
              </div>
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">
                  {{ $this->fmtHora(optional($this->evEtapa)->hora_inicio_invitado ?? $this->horaInicioInvitado) }}
                  -
                  {{ $this->fmtHora(optional($this->evEtapa)->hora_fin_invitado ?? $this->horaFinInvitado) }}
                </p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">
                {{ $this->fmtHora(optional($this->evEtapa)->hora_inicio ?? $this->horaInicio) }}
                -
                {{ $this->fmtHora(optional($this->evEtapa)->hora_fin ?? $this->horaFin) }}
              </p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @endif

        {{-- ===== BLOQUE ESPECIAL: 2ª Pre - Reasignación (solo si NO aceptó) ===== --}}
        @if ($this->isPreMediacion && $this->next == 2 && $this->ultima && (int)$this->ultima->acepta_proceso === 0)
        @if ($this->eventoSegPreMedicion)
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
              <span class="font-semibold">{{ $this->eventoSegPreMedicion->fecha }}</span>
            </div>
            <div class="flex items-center gap-2">
              <flux:icon.user class="w-4 h-4 text-emerald-600" />
              <span class="font-medium">Facilitador:</span>
              <span class="font-semibold">{{ optional($this->eventoSegPreMedicion->facilitador)->nombre ?? '—' }}</span>
            </div>

            @if ($this->opcionSeparados)
            <div class="mt-2 grid sm:grid-cols-2 gap-3">
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                <p class="text-sm font-semibold">
                  {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio) }} - {{
                  $this->fmtHora($this->eventoSegPreMedicion->hora_fin) }}
                </p>
              </div>
              <div
                class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                <p class="text-sm font-semibold">
                  {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio_invitado) }} - {{
                  $this->fmtHora($this->eventoSegPreMedicion->hora_fin_invitado) }}
                </p>
              </div>
            </div>
            @else
            <div
              class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
              <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
              <p class="text-sm font-semibold">
                {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio) }} - {{
                $this->fmtHora($this->eventoSegPreMedicion->hora_fin) }}
              </p>
            </div>
            @endif
          </flux:callout.text>
        </flux:callout>
        @else
        {{-- Formulario de crear evento de reasignación (inline) --}}
        <div
          class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
          <h3 class="text-base font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
            <flux:icon.calendar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Reasignación • Fecha y hora del evento
          </h3>

          <div class="grid grid-cols-2 gap-2">
            <div>
              <flux:select wire:model.defer="facilitador" placeholder="Elige facilitador disponible">
                @foreach ($this->facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
                @endforeach
              </flux:select>
            </div>

            @if (function_exists('view') && view()->exists('components.select-color'))
            <x-select-color model="colorEvento" :widthPx="340" />
            @else
            <flux:input wire:model.defer="colorEvento" label="Color (hex)" placeholder="#10B981" />
            @endif
          </div>

          <flux:input wire:model.defer="fechaNueva" type="date" label="Nueva fecha"
            placeholder="Seleccione la nueva fecha" />

          <div class="pt-2">
            @if (!$this->opcionSeparados)
            <div class="space-y-2">
              <p class="text-sm font-semibold dark:text-white">Horario (ambas partes)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
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
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioInvitadoEvento" placeholder="Hora inicio">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinInvitadoEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
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
        @endif
        @endif

        {{-- ===== EN LÍNEA / PRESENCIAL (formularios normales) ===== --}}
        @if ($this->modalidad == 2)
        @if ($this->isPrimera)
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Enlace / Liga" placeholder="https://meet.google.com/..." wire:model="enlaceReunion" />
          @error('enlaceReunion')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
        @else
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Enlace / Liga (nueva)" placeholder="https://meet.google.com/..."
            wire:model="urlNuevaInv" />
          @error('urlNuevaInv')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        @if ((int)($this->tipoProcesoId ?? 0) === 2)
        <div class="mt-3 grid gap-3 max-w-lg">
          <flux:input type="date" label="Fecha de atención (nueva)" wire:model="fechaAtencion" />
          @error('fechaAtencion')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
        <div class="mt-4">
          <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
            Horario
            <span x-show="!openHoras">▼</span>
            <span x-show="openHoras">▲</span>
          </button>

          <div x-show="openHoras" x-collapse x-cloak class="mt-2 grid grid-cols-2 gap-2 max-w-lg">
            <flux:select wire:model="horaInicio" placeholder="Hora inicio">
              @foreach ($this->horarios as $valor => $etiqueta)
              <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>

            <flux:select wire:model="horaFin" placeholder="Hora fin">
              @foreach ($this->horarios as $valor => $etiqueta)
              <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>

          @error('horaInicio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          @error('horaFin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        @endif
        @endif
        @else
        @if ($this->isPrimera)
        <div class="grid gap-3 max-w-lg">
          <flux:input label="Fecha de envío" type="date" wire:model="fechaEnvio" />
          @error('fechaEnvio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mt-4">
          <button type="button" class="text-sm underline flex items-center gap-2" @click="openHoras=!openHoras">
            Horario <span x-show="!openHoras">▼</span><span x-show="openHoras">▲</span>
          </button>
          <div x-show="openHoras" x-collapse x-cloak class="max-w-lg">
            <flux:select wire:model="horaInicio" placeholder="Hora envío">
              @foreach ($this->horarios as $valor => $etiqueta)
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
            <flux:select wire:model="horaInicio" placeholder="Hora envío">
              @foreach ($this->horarios as $valor => $etiqueta)
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
        @if ($this->esSegundaPre)
        @if ($this->ultima && (int)$this->ultima->acepta_proceso === 0)
        {{-- Mismo formulario inline de creación de evento mostrado arriba --}}
        <div
          class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
          <h3 class="text-base font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
            <flux:icon.calendar class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
            Reasignación • Fecha y hora del evento
          </h3>

          <div class="grid grid-cols-2 gap-2">
            <flux:select wire:model.defer="facilitador" placeholder="Elige facilitador disponible">
              @foreach ($this->facilitadores as $fac)
              <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
              @endforeach
            </flux:select>

            @if (function_exists('view') && view()->exists('components.select-color'))
            <x-select-color model="colorEvento" :widthPx="340" />
            @else
            <flux:input wire:model.defer="colorEvento" label="Color (hex)" placeholder="#10B981" />
            @endif
          </div>

          <flux:input wire:model.defer="fechaNueva" type="date" label="Nueva fecha"
            placeholder="Seleccione la nueva fecha" />

          <div class="pt-2">
            @if (!$this->opcionSeparados)
            <div class="space-y-2">
              <p class="text-sm font-semibold dark:text-white">Horario (ambas partes)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioEvento" placeholder="Hora inicio">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
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
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
              </div>

              <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
              <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicioInvitadoEvento" placeholder="Hora inicio">
                  @foreach ($this->horarios as $valor => $etiqueta)
                  <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                  @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFinInvitadoEvento" placeholder="Hora fin">
                  @foreach ($this->horarios as $valor => $etiqueta)
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
          <flux:callout.text>{{ $this->motivoBloqueo }}</flux:callout.text>
        </flux:callout>
        @endif
        @else
        <flux:callout color="warning">
          <flux:callout.heading>
            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
            Acción bloqueada
          </flux:callout.heading>
          <flux:callout.text>{{ $this->motivoBloqueo }}</flux:callout.text>
        </flux:callout>
        @endif
        @endif
      </div>
      {{-- ========= /CARD ========= --}}

      {{-- ========= LISTADO: Timeline ========= --}}
      <div
        class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <h4 class="font-semibold text-neutral-800 dark:text-neutral-100">Historial</h4>
          <div class="text-sm text-neutral-500">Total: {{ $this->invitaciones->count() }} {{ $this->etqUnidadPlural }}
          </div>
        </div>

        @if($this->invitaciones->isEmpty())
        <p class="text-sm text-neutral-600 dark:text-neutral-300">Aún no hay {{ $this->etqUnidadPlural }} registradas.
        </p>
        @else
        <div class="relative pl-6">
          <span class="absolute left-2 top-1 bottom-1 w-px bg-neutral-200 dark:bg-neutral-700"></span>

          @foreach($this->invitaciones as $inv)
          @php($chipLabel = is_null($inv->asistio) ? 'Pendiente' : ($inv->asistio ? 'Asistió' : 'No asistió'))
          @php($chipClass = is_null($inv->asistio)
          ? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300'
          : ($inv->asistio
          ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200'
          : 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200'))
          @php($dotClass = is_null($inv->asistio) ? 'bg-amber-400' : ($inv->asistio ? 'bg-emerald-500' : 'bg-rose-500'))

          <div class="relative mb-5" x-data="{ open: {{ $loop->first ? 'false' : 'true' }} }">
            <span
              class="absolute -left-[7px] top-2 h-3 w-3 rounded-full {{ $dotClass }} shadow ring-2 ring-white dark:ring-neutral-900"></span>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
              <div class="flex items-start justify-between gap-3">
                <div class="text-sm font-semibold">{{ $this->isPreMediacion ? 'Invitación' : 'Sesión' }} #{{
                  $inv->numero_inv }}</div>
                <div class="flex items-center gap-2">
                  <span class="text-[10px] px-2 py-1 rounded-full {{ $chipClass }}">{{ $chipLabel }}</span>

                  @if (!is_null($inv->acepta_proceso))
                  <span
                    class="text-[10px] px-2 py-1 rounded-full
                            {{ $inv->acepta_proceso ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/20 dark:text-emerald-300'
                                                    : 'bg-sky-100 text-sky-700 dark:bg-sky-700/20 dark:text-sky-300' }}">
                    {{ $this->isPreMediacion ? ($inv->acepta_proceso ? 'Aceptó mediación' : 'No aceptó')
                    : ($inv->acepta_proceso ? 'Con acuerdo' : 'Sin acuerdo') }}
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
                @php($ev = $this->eventoParaInv($inv))
                <div
                  class="mt-3 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-3">
                  <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                      {{ $this->isPreMediacion ? 'Invitación' : 'Sesión' }}
                    </p>

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
                    @if ($inv->hora_inicio || $inv->hora_fin || $this->modalidad == 1)
                    <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-[11px] text-neutral-500">
                        {{ $inv->modalidad == 2 ? 'Horario (invitación)' : 'Hora envío (capturada)' }}
                      </p>
                      <p class="text-sm font-medium">
                        {{ $inv->hora_inicio ? $this->fmtHora($inv->hora_inicio) : '—' }}
                        @if($inv->hora_fin) - {{ $this->fmtHora($inv->hora_fin) }} @endif
                      </p>
                    </div>
                    @elseif($this->modalidad == 2)
                    @php($sep = (int)(optional($ev)->opcion_invitacion ?? 1) === 0)
                    @if (!$sep)
                    @if (optional($ev)->hora_inicio && optional($ev)->hora_fin)
                    <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2 inline-block">
                      <p class="text-[11px] text-neutral-500">Horario (evento)</p>
                      <p class="text-sm font-medium">{{ $this->fmtHora(optional($ev)->hora_inicio) }} - {{
                        $this->fmtHora(optional($ev)->hora_fin) }}</p>
                    </div>
                    @else
                    <p class="text-neutral-500 text-xs">Sin horario definido en el evento.</p>
                    @endif
                    @else
                    <div class="grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">
                          {{ optional($ev)->hora_inicio ? $this->fmtHora(optional($ev)->hora_inicio) : '—' }}
                          -
                          {{ optional($ev)->hora_fin ? $this->fmtHora(optional($ev)->hora_fin) : '—' }}
                        </p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">
                          {{ optional($ev)->hora_inicio_invitado ? $this->fmtHora(optional($ev)->hora_inicio_invitado) :
                          '—' }}
                          -
                          {{ optional($ev)->hora_fin_invitado ? $this->fmtHora(optional($ev)->hora_fin_invitado) : '—'
                          }}
                        </p>
                      </div>
                    </div>
                    @endif
                    @endif
                    @else
                    <div class="grid sm:grid-cols-2 gap-3">
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Solicitante(s)</p>
                        <p class="text-sm font-medium">
                          {{ $inv->hora_inicio ? $this->fmtHora($inv->hora_inicio) : '—' }} - {{ $inv->hora_fin ?
                          $this->fmtHora($inv->hora_fin) : '—' }}
                        </p>
                      </div>
                      <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-2">
                        <p class="text-[11px] text-neutral-500">Invitado(s)</p>
                        <p class="text-sm font-medium">
                          {{ $inv->hora_inicio_invitado ? $this->fmtHora($inv->hora_inicio_invitado) : '—' }} - {{
                          $inv->hora_fin_invitado ? $this->fmtHora($inv->hora_fin_invitado) : '—' }}
                        </p>
                      </div>
                    </div>
                    @endif
                  </div>

                  <div class="mt-2 text-xs">
                    <span
                      class="px-2 py-1 rounded-full bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300">
                      Facilitador (invitación): {{ optional($inv->facilitador)->nombre ?? '—' }}
                    </span>
                    @if ($ev && $inv->facilitador && optional($ev->facilitador)->nombre !==
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
      <div class="md:sticky md:top-4 md:self-start space-y-4">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
          <div class="text-sm font-semibold mb-2">Resumen</div>
          <dl class="text-sm space-y-2">
            <div class="flex justify-between">
              <dt class="text-neutral-500">Etapa</dt>
              <dd class="font-medium">{{ $this->isPreMediacion ? 'Pre-mediación' : $this->etiquetaEtapa2 }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Siguiente #</dt>
              <dd class="font-medium">#{{ $this->next }}</dd>
            </div>
            @if(!$this->isPrimera && $this->ultima)
            <div class="flex justify-between">
              <dt class="text-neutral-500">Última {{ $this->etqUnidadSing }}</dt>
              <dd>#{{ $this->ultima->numero_inv }}</dd>
            </div>
            <div class="flex justify-between">
              <dt class="text-neutral-500">Asistencia</dt>
              <dd>{{ is_null($this->ultima->asistio) ? 'Pendiente' : ($this->ultima->asistio ? 'Sí' : 'No') }}</dd>
            </div>
            @endif
          </dl>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-900">
          @if($this->bloqueoNueva || $this->mostrarPreguntaAceptacion || $this->bloqueoAsistencia)
          <flux:tooltip content="{{ $this->tooltipMsg }}">
            <div>
              <flux:button class="w-full" disabled variant="primary" icon="lock-closed">{{ $this->textoBtn }}
              </flux:button>
            </div>
          </flux:tooltip>
          @else
          <flux:button class="w-full" wire:click="store('{{ $this->accionClick }}')" variant="primary"
            wire:loading.attr="disabled">
            {{ $this->textoBtn }}
          </flux:button>
          @endif
          <div wire:loading wire:target="store" class="mt-2 text-xs text-neutral-500">Guardando…</div>
        </div>
      </div>
    </aside>
  </div>
  @endif
</div>
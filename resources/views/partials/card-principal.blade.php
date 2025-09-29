<div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 bg-white dark:bg-neutral-900 shadow-sm"
             x-data="{ openHoras: true }">

          {{-- Resumen compacto --}}
          <div class="mb-4 flex flex-wrap items-center gap-2 text-[11px]">
            <span class="px-2 py-1 rounded-full {{ $this->chipProceso['class'] }}">{{ $this->chipProceso['label'] }}</span>

            @if ($this->chipAsistencia)
              <span class="px-2 py-1 rounded-full {{ $this->chipAsistencia['class'] }}">{{ $this->chipAsistencia['label'] }}</span>
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
                <span class="text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-700/20 dark:text-amber-300">
                  Bloqueada
                </span>
              </flux:tooltip>
            @endif
          </div>

          <div class="h-px bg-neutral-100 dark:bg-neutral-800 mb-4"></div>

          {{-- ===== PREGUNTA (solo si asistió y falta aceptar/rechazar) ===== --}}
          @if ($this->mostrarPreguntaAceptacion)
            <div class="mt-0">
              <div class="rounded-lg border border-emerald-300/50 dark:border-emerald-700/50 p-3 bg-emerald-50/50 dark:bg-emerald-900/10"
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Generar documentos
                      </a>
                    </div>

                    <livewire:dropzone wire:model="manifestaciones" :rules="['mimes:pdf','max:10420']" :multiple="true" class="w-full" />
                    @error('manifestaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="manifestaciones" class="mt-1 text-xs text-neutral-500">Subiendo documentos…</div>
                  </div>
                </div>

                <div class="mt-3">
                  <flux:button size="sm" variant="primary" wire:click="confirmarResultadoEtapa">Confirmar</flux:button>
                </div>
              </div>
            </div>

          {{-- 🔒 Asistencia pendiente: NO mostrar formularios de nueva agendación --}}
          @elseif ($this->bloqueoAsistencia)
            <flux:callout color="warning">
              <flux:callout.heading>
                <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-500" />
                Falta registrar asistencia
              </flux:callout.heading>
              <flux:callout.text>
                Primero registra la asistencia de la última {{ $this->isPreMediacion ? 'invitación' : 'sesión' }} para habilitar
                la nueva {{ $this->etqUnidadSing }}.
              </flux:callout.text>
            </flux:callout>

          {{-- ===== FORMULARIOS (solo si NO hay pregunta y no bloqueado) ===== --}}
          @elseif(!$this->bloqueoNueva)

            {{-- Detalles del evento inicial --}}
            @if ($this->isPrimera && $this->evEtapa)
              <flux:callout color="neutral" class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
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
                      <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                        <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                        <p class="text-sm font-semibold">
                          {{ $this->fmtHora(optional($this->evEtapa)->hora_inicio ?? $this->horaInicio) }}
                          -
                          {{ $this->fmtHora(optional($this->evEtapa)->hora_fin ?? $this->horaFin) }}
                        </p>
                      </div>
                      <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                        <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                        <p class="text-sm font-semibold">
                          {{ $this->fmtHora(optional($this->evEtapa)->hora_inicio_invitado ?? $this->horaInicioInvitado) }}
                          -
                          {{ $this->fmtHora(optional($this->evEtapa)->hora_fin_invitado ?? $this->horaFinInvitado) }}
                        </p>
                      </div>
                    </div>
                  @else
                    <div class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
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
                <flux:callout color="neutral" class="mb-4 border-l-4 border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20">
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
                        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                          <p class="text-xs text-neutral-500 mb-1">Solicitante(s)</p>
                          <p class="text-sm font-semibold">
                            {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio) }} - {{ $this->fmtHora($this->eventoSegPreMedicion->hora_fin) }}
                          </p>
                        </div>
                        <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                          <p class="text-xs text-neutral-500 mb-1">Invitado(s)</p>
                          <p class="text-sm font-semibold">
                            {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio_invitado) }} - {{ $this->fmtHora($this->eventoSegPreMedicion->hora_fin_invitado) }}
                          </p>
                        </div>
                      </div>
                    @else
                      <div class="mt-2 rounded-lg border border-emerald-200 dark:border-emerald-700 p-3 bg-white dark:bg-neutral-800 shadow-sm">
                        <p class="text-xs text-neutral-500 mb-1">Horario (evento)</p>
                        <p class="text-sm font-semibold">
                          {{ $this->fmtHora($this->eventoSegPreMedicion->hora_inicio) }} - {{ $this->fmtHora($this->eventoSegPreMedicion->hora_fin) }}
                        </p>
                      </div>
                    @endif
                  </flux:callout.text>
                </flux:callout>
              @else
                {{-- Formulario de crear evento de reasignación (inline) --}}
                <div class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
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

                  <flux:input wire:model.defer="fechaNueva" type="date" label="Nueva fecha" placeholder="Seleccione la nueva fecha" />

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
                    Una vez creado el evento de reasignación, este formulario se habilitará automáticamente para crear la invitación #2.
                  </p>
                </div>
              @endif
            @endif

            {{-- ===== EN LÍNEA / PRESENCIAL (formularios normales) ===== --}}
            @if ($this->modalidad == 2)
              @if ($this->isPrimera)
                <div class="grid gap-3 max-w-lg">
                  <flux:input label="Enlace / Liga" placeholder="https://meet.google.com/..." wire:model="enlaceReunion" />
                  @error('enlaceReunion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
              @else
                <div class="grid md:grid-cols-2 gap-3 max-w-2xl">
                  <div>
                    <flux:input label="Enlace / Liga (nueva)" placeholder="https://meet.google.com/..." wire:model="urlNuevaInv" />
                    @error('urlNuevaInv') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                  </div>
                </div>
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
                <div class="mt-0 rounded-xl border border-emerald-300/50 dark:border-emerald-700/50 bg-emerald-50/40 dark:bg-emerald-900/10 p-4 space-y-4">
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

                  <flux:input wire:model.defer="fechaNueva" type="date" label="Nueva fecha" placeholder="Seleccione la nueva fecha" />

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
                    Una vez creado el evento de reasignación, este formulario se habilitará automáticamente para crear la invitación #2.
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
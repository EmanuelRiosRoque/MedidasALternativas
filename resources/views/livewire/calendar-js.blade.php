<div class="max-w-4xl mx-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-neutral-800 dark:text-white">

    {{-- Lista de eventos --}}
    <div class="bg-neutral-100 dark:bg-neutral-700 p-4 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-2 text-neutral-800 dark:text-neutral-100 flex items-center justify-between">
            Eventos del día
            <div wire:loading wire:target='seleccionarDia' class="ml-4">
                <div class="animate-spin rounded-full h-6 w-6 border-t-2 border-b-2 border-emerald-600"></div>
            </div>
        </h2>

        <hr class="border-neutral-300 dark:border-neutral-600 mb-4">

        <ul
  class="space-y-3 text-sm text-neutral-600 dark:text-neutral-200
         max-h-[28vh] overflow-y-auto pr-1"  {{-- 👈 limita altura y habilita scroll --}}
>
    @forelse ($eventos as $evento)
        <li wire:click="editarEvento({{ $evento->id }})"
            class="flex rounded-md transition shadow-md hover:bg-neutral-200 dark:hover:bg-neutral-600 hover:shadow-lg hover:scale-[1.01] hover:ring-2 hover:ring-emerald-500 overflow-hidden cursor-pointer duration-150">
            <div class="w-2 {{ $evento->color }}"></div>
            <div class="flex-1 p-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-semibold flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $evento->color }}"></div>
                        {{ $evento->solicitud?->folio_materia ?? 'Solicitud #'.($evento->solicitud?->id ?? 'N/D') }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full font-semibold bg-neutral-200 dark:bg-neutral-800 text-neutral-800 dark:text-white">
                        {{ $evento->estatus?->nombre ?? '—' }}
                    </span>
                </div>

                <div class="text-xs text-neutral-500 dark:text-neutral-300 flex flex-wrap gap-x-2">
                    @if ($evento->hora_inicio && $evento->hora_fin)
                        <span>
                            @if (!$evento->opcion_invitacion)
                                <span class="font-semibold">Solicitante:</span>
                            @endif
                            {{ substr($evento->hora_inicio, 0, 5) }} - {{ substr($evento->hora_fin, 0, 5) }}
                        </span>
                    @else
                        <span>Sin horario</span>
                    @endif

                    @if ($evento->hora_inicio_invitado && $evento->hora_fin_invitado)
                        <span>|</span>
                        <span>
                            <span class="font-semibold">Invitado:</span>
                            {{ substr($evento->hora_inicio_invitado, 0, 5) }} - {{ substr($evento->hora_fin_invitado, 0, 5) }}
                        </span>
                    @endif

                    <span>|</span>
                    <span>
                        <span class="font-semibold">Facilitador:</span>
                        {{ $evento->facilitador?->nombre ?? 'Sin asignar' }}
                    </span>
                </div>
            </div>
        </li>
    @empty
        <li class="italic text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
            <svg class="w-4 h-4 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 0 1 4-4h4" />
            </svg>
            No hay eventos para este día.
        </li>
    @endforelse
</ul>

    </div>

    {{-- Calendario --}}
    <div class="p-4 bg-neutral-200 dark:bg-neutral-600 rounded-lg shadow">
        <div class="flex items-center justify-between mb-2">
            <button wire:click="cambiarMes(-1)"
                class="text-neutral-600 dark:text-neutral-300 hover:text-black dark:hover:text-white">&lt;</button>
            <h2 class="text-lg font-semibold text-neutral-800 dark:text-white">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth)->translatedFormat('F Y') }}
            </h2>
            <button wire:click="cambiarMes(1)"
                class="text-neutral-600 dark:text-neutral-300 hover:text-black dark:hover:text-white">&gt;</button>
        </div>

        {{-- Encabezados de lunes a viernes --}}
        <div class="grid grid-cols-5 text-center text-sm text-neutral-500 dark:text-neutral-400 mb-1">
            @foreach (['L', 'M', 'M', 'J', 'V'] as $dia)
            <div>{{ $dia }}</div>
            @endforeach
        </div>

        {{-- Días del mes: solo lunes a viernes --}}
        <div class="grid grid-cols-5 gap-1 text-sm text-center">
            @php
            $inicioMes = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
            $finMes = $inicioMes->copy()->endOfMonth();
            @endphp

            @for ($fecha = $inicioMes->copy(); $fecha->lte($finMes); $fecha->addDay())
            @php $dayOfWeek = $fecha->dayOfWeek; @endphp

            @if ($dayOfWeek >= 1 && $dayOfWeek <= 5) @php $day=$fecha->day;
                $fechaStr = $fecha->toDateString();
                $hoy = now()->toDateString() === $fechaStr;
                // usa diasConEventos (más barato que filtrar $eventos)
                $hayEventos = in_array($day, $diasConEventos, true);
                $seleccionado = $fechaSeleccionada === $fechaStr;
                @endphp

                <div wire:click="seleccionarDia({{ $day }})" class="cursor-pointer p-2 rounded-full transition
                         {{ $seleccionado ? 'bg-emerald-700 text-white ring-2 ring-white' : ($hoy ? 'bg-emerald-500 text-white' : 'hover:bg-emerald-400') }}
                         {{ $hayEventos ? 'border border-emerald-300 shadow-sm' : '' }}">
                    {{ $day }}
                </div>
                @endif
                @endfor
        </div>

        <div class="mt-4 text-center">
            <x-flux::button wire:click="abrirModalDia" variant="primary"
                class="w-full hover:scale-105 transition-transform duration-150">
                Agregar evento
            </x-flux::button>
        </div>
    </div>

    {{-- Modal --}}
    <flux:modal wire:model="showModalDia" class="md:w-96 transition-all duration-300 ease-out">
        <div class="space-y-6" x-data="{ mostrarObservacion: $wire.entangle('reasignacion') }">

            <div>
                <flux:heading size="lg">
                    {{ $modoEditar ? 'Actualizar el evento del día' : 'Crear evento para el día' }}: {{
                    $fechaSeleccionada }}
                </flux:heading>
                <flux:text class="mt-2">Información relevante del evento seleccionado.</flux:text>
            </div>

            @if ($modoEditar)
            <div class="space-y-4">
                <flux:radio.group x-model="mostrarObservacion" label="¿Reasignación?">
                    <flux:radio value="1" label="Sí" />
                    <flux:radio value="2" label="No" />
                </flux:radio.group>

                <template x-if="mostrarObservacion == 1">
                    <flux:input wire:model.defer="observacion" label="Motivo de reasignación"
                        placeholder="Ingrese el motivo" />
                </template>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="facilitador" placeholder="Elige facilitador disponible"
                    x-bind:disabled="{{ $modoEditar ? 'mostrarObservacion == 2' : 'false' }}">
                    @foreach ($facilitadores as $facilitador)
                    <flux:select.option value="{{ $facilitador->id }}">{{ $facilitador->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>

                <x-select-color wire:model.defer="colorEvento" />
            </div>

            @if (!$modoEditar && $solicitudes->isNotEmpty())
            {{-- sin .live para disparar updatedSolicitud correctamente --}}
            <div class="relative">
                <flux:select wire:model.live="solicitud" placeholder="Elige solicitud a asignar"
                    wire:loading.attr="disabled" wire:target="solicitud" aria-busy="@js($this->isNotNull('solicitud'))">
                    @foreach ($solicitudes as $solicitud)
                    <flux:select.option value="{{ $solicitud->id }}">
                        {{ $solicitud->folio_materia }}
                    </flux:select.option>
                    @endforeach
                </flux:select>

                {{-- Spinner dentro del select --}}
                <div class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" wire:loading
                    wire:target="solicitud">
                    <svg class="h-4 w-4 animate-spin text-neutral-500" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-opacity=".2"
                            stroke-width="4" />
                        <path d="M22 12a10 10 0 0 1-10 10" fill="none" stroke="currentColor" stroke-width="4"
                            stroke-linecap="round" />
                    </svg>
                </div>
            </div>

            {{-- Mensaje bajo el select mientras carga --}}
            <p class="text-xs text-neutral-500" wire:loading.delay wire:target="solicitud">
                Procesando…
            </p>

            @elseif (!$modoEditar)
            <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">
                No hay solicitudes disponibles.
            </p>
            @endif

            {{-- Mostrar horarios sólo si ya hay decisión (true/false) o si estás editando --}}
            @if ($modoEditar || !is_null($acudiran_juntos))

            {{-- Si acuden juntos --}}
            @if ($acudiran_juntos === true)
            <div class="grid grid-cols-2 gap-2">
                <flux:select wire:model.defer="horaInicio" placeholder="Hora inicio">
                    @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.defer="horaFin" placeholder="Hora fin">
                    @foreach ($horarios as $valor => $etiqueta)
                    <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            @endif

            {{-- Si acuden separados --}}
            @if ($acudiran_juntos === false)
            <div class="space-y-4">
                <p class="text-sm font-semibold dark:text-white">Horario para solicitante(s)</p>
                <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model.defer="horaInicio" placeholder="Hora inicio">
                        @foreach ($horarios as $valor => $etiqueta)
                        <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model.defer="horaFin" placeholder="Hora fin">
                        @foreach ($horarios as $valor => $etiqueta)
                        <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <p class="text-sm font-semibold dark:text-white">Horario para invitado(s)</p>
                <div class="grid grid-cols-2 gap-2">
                    <flux:select wire:model.defer="horaInicioInvitado" placeholder="Hora inicio">
                        @foreach ($horarios as $valor => $etiqueta)
                        <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model.defer="horaFinInvitado" placeholder="Hora fin">
                        @foreach ($horarios as $valor => $etiqueta)
                        <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
            @endif

            @endif

            <flux:input wire:model.defer="actividad" label="Actividad" placeholder="Nombre de la actividad" />

            @if ($modoEditar)
            <flux:input wire:model.defer="fechaNueva" type="date" label="Cambiar fecha"
                placeholder="Ingrese la observación" />
            @endif

            <div class="flex justify-end">
                <flux:button type="button" wire:click="cerrar" class="mr-2">Cerrar</flux:button>
                <flux:button type="button" wire:click="guardarEvento" variant="primary">
                    @if ($reasignacion == 1)
                    Confirmar Reasignación
                    @else
                    {{ $modoEditar ? 'Actualizar' : 'Agregar' }}
                    @endif
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
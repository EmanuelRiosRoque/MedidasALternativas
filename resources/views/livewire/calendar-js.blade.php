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

        <ul class="space-y-3 text-sm text-neutral-600 dark:text-neutral-200">
            @forelse ($eventos as $evento)
            <li wire:click="editarEvento({{ $evento->id }})"
                class="flex rounded-md transition shadow-md hover:bg-neutral-200 dark:hover:bg-neutral-600 hover:shadow-lg hover:scale-[1.01] hover:ring-2 hover:ring-emerald-500 overflow-hidden cursor-pointer duration-150">
                <div class="w-2 {{ $evento->color }}"></div>
                <div class="flex-1 p-5">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full {{ $evento->color }}"></div>
                            {{ $evento->solicitud->nombre ?? 'Solicitud #' . $evento->solicitud->id }}
                        </span>
                        <span
                            class="px-2 py-1 text-xs rounded-full font-semibold bg-neutral-200 dark:bg-neutral-800 text-neutral-800 dark:text-white">
                            {{ $evento->estatus->nombre }}
                        </span>
                    </div>
                   <div class="text-xs text-neutral-500 dark:text-neutral-300 flex flex-wrap gap-x-2">
                        @if ($evento->hora_inicio && $evento->hora_fin)
                            <span>
                                @if ($evento->opcion_invitacion != "todos")
                                <span class="font-semibold">Solicitante:</span>
                                @endif
                                {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i') }}
                            </span>
                        @else
                            <span>Sin horario</span>
                        @endif

                        @if ($evento->hora_inicio_invitado && $evento->hora_fin_invitado)
                            <span>|</span>
                            <span>
                                <span class="font-semibold">Invitado:</span>
                                {{ \Carbon\Carbon::parse($evento->hora_inicio_invitado)->format('H:i') }} - {{ \Carbon\Carbon::parse($evento->hora_fin_invitado)->format('H:i') }}
                            </span>
                        @endif

                        <span>|</span>
                        <span>
                            <span class="font-semibold">Facilitador:</span>
                            {{ $evento->facilitador->nombre ?? 'Sin asignar' }}
                        </span>
                    </div>


                </div>
            </li>
            @empty
            <li class="italic text-neutral-500 dark:text-neutral-400 flex items-center gap-2">
                <svg class="w-4 h-4 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4" />
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
            @php
            $dayOfWeek = $fecha->dayOfWeek; // 0 (domingo) - 6 (sábado)
            @endphp

            @if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {{-- Lunes a Viernes --}} @php $day=$fecha->day;
                $fechaStr = $fecha->toDateString();
                $hoy = now()->toDateString() === $fechaStr;
                $seleccionado = $fechaSeleccionada === $fechaStr;
                $hayEventos = $eventos->where('fecha', $fechaStr)->isNotEmpty();
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
        <div class="space-y-6"
            x-data="{ mostrarObservacion: $wire.entangle('reasignacion') }"
        >

            <div>
                <flux:heading size="lg"> {{ $modoEditar ? 'Actualziar el evento del día' : 'Crear evento para el día'
                    }}: {{ $fechaSeleccionada }}</flux:heading>
                <flux:text class="mt-2">Información relevante del evento seleccionado.</flux:text>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <flux:select
                    wire:model="facilitador"
                    placeholder="Elige facilitador disponible"
                    x-bind:disabled="{{ $modoEditar ? 'mostrarObservacion == 2' : 'false' }}"
                >
                    @foreach ($facilitadores as $facilitador)
                        <flux:select.option value="{{ $facilitador->id }}">{{ $facilitador->nombre }}</flux:select.option>
                    @endforeach
                </flux:select>

                <x-select-color wire:model="colorEvento" />
            </div>


            @if (!$modoEditar && $solicitudes->isNotEmpty())
            <flux:select wire:model.live="solicitud" placeholder="Elige solicitud a asignar">
                @foreach ($solicitudes as $solicitud)
                <flux:select.option value="{{ $solicitud->id }}">
                    {{ $solicitud->nombre ?? 'Solicitud #' . $solicitud->id }}
                </flux:select.option>
                @endforeach
            </flux:select>
            @elseif (!$modoEditar)
            <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">
                No hay solicitudes disponibles.
            </p>
            @endif

            @if (!empty($solicitantes))
            <div x-data="{ modo: $wire.entangle('solicitante') }" class="space-y-4">

                <flux:select x-model="modo" wire:model='solicitante' placeholder="Elige un solicitante o invitado">
                    <flux:select.option value="todos">TODOS (ACUDIRÁN JUNTOS)</flux:select.option>
                    <flux:select.option value="separados">SEPARADO (S)</flux:select.option>
                </flux:select>

                <!-- Para modo SEPARADO -->
                <template x-if="modo === 'separados'">
                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-white">Horario para solicitante(s)</p>
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

                        <p class="text-sm font-semibold text-white">Horario para invitado(s)</p>
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
                </template>

                <!-- Para modo TODOS -->
                <template x-if="modo === 'todos'">
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
                </template>

            </div>
            @endif
                    

            <flux:input wire:model="actividad" label="Actividad" placeholder="Nombre de la actividad" />

            @if ($modoEditar)
            <flux:input wire:model="fechaNueva" type="date" label="Cambiar fecha" placeholder="Ingrese la observación" />

            <div class="space-y-4">
                <flux:radio.group x-model="mostrarObservacion" label="¿Reasignación?">
                    <flux:radio value="1" label="Sí" />
                    <flux:radio value="2" label="No" />
                </flux:radio.group>

                <template x-if="mostrarObservacion == 1">
                    <flux:input wire:model="observacion" label="Observación" placeholder="Ingrese la observación" />
                </template>
            </div>
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
<div class="max-w-4xl mx-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-white">
    <!-- Lista de eventos -->
    <div class="bg-neutral-700 p-4 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4 text-neutral-100">Eventos del día</h2>

        <ul class="space-y-3 text-sm text-neutral-200">
            @forelse ($eventos as $evento)
                <li wire:click="editarEvento({{ $evento->id }})"
                    class="flex rounded-md transition shadow-md hover:bg-neutral-600 hover:shadow-lg overflow-hidden cursor-pointer">
                    <div class="w-2 {{ $evento->color }}"></div>
                    <div class="flex-1 p-5">
                        <div class="font-semibold text-white">
                            {{ $evento->solicitud->nombre ?? 'Solicitud #' . $evento->solicitud->id }}
                        </div>
                        <div class="text-xs text-neutral-200">
                            {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }}
                            - {{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i') }}
                            | Facilitador: {{ $evento->facilitador->nombre ?? 'Sin asignar' }}
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-neutral-400 italic">No hay eventos para este día.</li>
            @endforelse
        </ul>

    </div>



    <!-- Calendario -->
    <div class="p-4 bg-neutral-600 rounded-lg shadow">
        <div class="flex items-center justify-between mb-2">
            <button wire:click="cambiarMes(-1)" class="text-neutral-300 hover:text-white">&lt;</button>
            <h2 class="text-lg font-semibold">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth)->translatedFormat('F Y') }}
            </h2>
            <button wire:click="cambiarMes(1)" class="text-neutral-300 hover:text-white">&gt;</button>
        </div>

        <div class="grid grid-cols-7 text-center text-sm text-neutral-400 mb-1">
            @foreach (['L', 'M', 'M', 'J', 'V', 'S', 'D'] as $dia)
            <div>{{ $dia }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-1 text-sm text-center">
            @for ($i = 1; $i < $primerDiaMes; $i++) <div>
        </div>
        @endfor

        @for ($day = 1; $day <= $diasEnMes; $day++) @php $fecha=\Carbon\Carbon::create($currentYear, $currentMonth,
            $day)->toDateString();
            $hoy = now()->toDateString() === $fecha;
            $seleccionado = $fechaSeleccionada === $fecha;
            @endphp
            <div wire:click="seleccionarDia({{ $day }})"
                class="cursor-pointer p-2 rounded-full transition
                        {{ $seleccionado ? 'bg-emerald-700 text-white' : ($hoy ? 'bg-emerald-500 text-white' : 'hover:bg-emerald-400') }}">
                {{ $day }}
            </div>
            @endfor
    </div>
    <div class="mt-4 text-center">
        <x-flux::button wire:click="abrirModalDia" variant="primary" class="w-full">
            Agregar evento
        </x-flux::button>
    </div>
</div>


<flux:modal wire:model="showModalDia" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Crear evento para el dia: {{ $fechaSeleccionada }}</flux:heading>
            <flux:text class="mt-2">Información relevante del evento seleccionado.</flux:text>
        </div>

        <div class=" grid grid-cols-2 gap-2">
            <flux:select wire:model="facilitador" placeholder="Elige facilitador disponible">
                @foreach ($facilitadores as $facilitador)
                <flux:select.option value="{{ $facilitador->id }}">{{ $facilitador->nombre }}</flux:select.option>
                @endforeach
            </flux:select>

            <x-select-color wire:model="colorEvento" />
        </div>


        @if($solicitudes->isNotEmpty())
        <flux:select wire:model.change="solicitud" placeholder="Elige solicitud a asignar">
            @foreach ($solicitudes as $solicitud)
            <flux:select.option value="{{ $solicitud->id }}">
                {{ $solicitud->nombre ?? 'Solicitud #' . $solicitud->id }}
            </flux:select.option>
            @endforeach
        </flux:select>
        @else
        <p class="text-sm text-gray-500 dark:text-gray-400 italic">
            No hay solicitudes disponibles.
        </p>
        @endif

        {{-- Mostrar solicitantes si hay --}}
        @if (!empty($solicitantes))        
            @if (!empty($solicitantes))
                <flux:select wire:model="solicitante" placeholder="Elige un solicitante o invitado">
                    <flux:select.option value="todos">TODOS</flux:select.option>
                    <flux:select.option value="solicitantes">TODOS LOS SOLICITANTES</flux:select.option>
                    <flux:select.option value="invitados">TODOS LOS INVITADOS</flux:select.option>
                    @foreach ($solicitantes as $solicitante)
                        <flux:select.option value="{{ $solicitante->id }}">
                            {{ strtoupper($solicitante->nombre ?? 'SIN NOMBRE') }} – {{ strtoupper($solicitante->tipo_solicitante ?? 'TIPO DESCONOCIDO') }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                    No hay solicitantes disponibles.
                </p>
            @endif
        @endif

        <div class=" grid grid-cols-2 gap-2">
            <flux:select wire:model="horaInicio" placeholder="Elige hora incio">
                @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="horaFin" placeholder="Elige hora fin">
                @foreach ($horarios as $valor => $etiqueta)
                <flux:select.option value="{{ $valor }}">{{ $etiqueta }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        <flux:input wire:model="actividad" label="Actividad" placeholder="Nombre de la actividad" />

        <flux:input wire:model="motivo_reasignacion" label="Motivo reasignacion" placeholder="Ingrese el motivo" />

        <div class="flex justify-end">
            <flux:button type="button" wire:click="cerrar" class="mr-2">Cerrar</flux:button>
            @if ($modoEditar)
                <flux:button type="button" wire:click="cerrar" class="mr-2">Reasignar</flux:button>
            @endif
            <flux:button type="button" wire:click="guardarEvento" variant="primary">{{ $modoEditar ? 'Actualizar' : 'Agregar' }}</flux:button>

        </div>
    </div>
</flux:modal>

</div>
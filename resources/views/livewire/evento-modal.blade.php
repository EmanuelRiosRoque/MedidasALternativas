<div>
    <flux:modal wire:model="show" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Detalle del Evento</flux:heading>
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
                <flux:select wire:model="solicitud" placeholder="Elige solicitud a asignar">
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

            <flux:input wire:model="actividad" label="Avtividad" placeholder="Nombre de la actividad" />

            <div class="flex justify-end">
                <flux:button type="button" wire:click="cerrar"  class="mr-2">Cerrar</flux:button>
                <flux:button type="button" wire:click="guardarEvento" variant="primary">Actualizar</flux:button>
            </div>
        </div>
    </flux:modal>

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
                <flux:select wire:model="solicitud" placeholder="Elige solicitud a asignar">
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

            <flux:input wire:model="actividad" label="Avtividad" placeholder="Nombre de la actividad" />

            <div class="flex justify-end">
                <flux:button type="button" wire:click="cerrar"  class="mr-2">Cerrar</flux:button>
                <flux:button type="button" wire:click="guardarEvento" variant="primary">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>

</div>

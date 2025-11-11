{{-- Modal: asignar facilitador solicitante --}}
<flux:modal name="asignar-facilitador-solicitante" class="w-full max-w-md overflow-visible">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Elige al facilitador (Solicitantes)</flux:heading>
            <flux:text class="mt-2">Selecciona quién facilitará la parte solicitante.</flux:text>
        </div>

        <flux:select wire:model="facilitadorSolicitanteId" placeholder="Seleccione un facilitador...">
            @forelse ($facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
            @empty
                <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
            @endforelse
        </flux:select>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Fecha --}}
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                    Fecha de asignación
                </label>
                <div class="sm:col-span-3 overflow-visible">
                  

                     <x-ui.date
    name="fecha_nacimiento_solicitante"
    wire:model="fechaAsignacionSolicitante"
/>
                </div>
                @error('fechaAsignacionSolicitante')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Horas --}}
            <div class="grid grid-cols-2 gap-1">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                        De:
                    </label>
                    <flux:input type="time" step="60" wire:model="horaInicioSolicitante" />
                    @error('horaInicioSolicitante')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                        A:
                    </label>
                    <flux:input type="time" step="60" wire:model="horaFinSolicitante" />
                    @error('horaFinSolicitante')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        @error('facilitadorSolicitanteId')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-end pt-2">
            <flux:button wire:click="guardarFacilitadorSolicitante" variant="primary">Guardar</flux:button>
        </div>
    </div>
</flux:modal>
{{-- End Modal: asignar facilitador solicitante --}}


{{-- Modal: asignar facilitador invitado --}}
<flux:modal name="asignar-facilitador-invitado" class="w-full max-w-md overflow-visible">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Elige al facilitador (Invitados)</flux:heading>
            <flux:text class="mt-2">Selecciona quién facilitará la parte invitada.</flux:text>
        </div>

        <flux:select wire:model="facilitadorInvitadoId" placeholder="Seleccione un facilitador...">
            @forelse ($facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
            @empty
                <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
            @endforelse
        </flux:select>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Fecha --}}
            <div>
                <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                    Fecha de asignación
                </label>
                <div class="sm:col-span-3 overflow-visible">
                    

                    <x-ui.date
    name="fecha_nacimiento_solicitante"
    wire:model="fechaAsignacionInvitado"
/>
                </div>
                @error('fechaAsignacionInvitado')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Horas --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                        De:
                    </label>
                    <flux:input type="time" step="60" wire:model="horaInicioInvitado" />
                    @error('horaInicioInvitado')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-200 mb-1">
                        A:
                    </label>
                    <flux:input type="time" step="60" wire:model="horaFinInvitado" />
                    @error('horaFinInvitado')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        @error('facilitadorInvitadoId')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-end pt-2">
            <flux:button wire:click="guardarFacilitadorInvitado" variant="primary">Guardar</flux:button>
        </div>
    </div>
</flux:modal>
{{-- End Modal: asignar facilitador invitado --}}


{{-- Modal: co-mediador --}}
<flux:modal name="co-mediador" class="w-full max-w-md overflow-visible">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Elige al co-mediador</flux:heading>
            <flux:text class="mt-2">Selecciona un facilitador para asignarlo como co-mediador.</flux:text>
        </div>

        <flux:select wire:model="coMediadorId" placeholder="Seleccione un co-mediador...">
            @forelse ($facilitadores as $fac)
                <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
            @empty
                <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
            @endforelse
        </flux:select>

        @error('coMediadorId')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <div class="flex justify-end pt-2">
            <flux:button wire:click="guardarCoMediador" variant="primary">Guardar</flux:button>
        </div>
    </div>
</flux:modal>
{{-- End Modal: co-mediador --}}

<div class="py-3">
    <h1 class="text-base font-semibold uppercase text-emerald-400 tracking-wider mb-4">
        Datos de Contacto
    </h1>

    <div class="grid grid-cols-2 gap-6">
        <!-- Correos -->
        <div class="p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Correos</h2>

            @foreach ($correos as $index => $correo)
                <div class="flex items-center gap-3 mb-3">
                    <flux:input type="email" wire:model.defer="correos.{{ $index }}.email" class="flex-1" />
                    <flux:button wire:click="actualizarCorreo({{ $index }})">
                        Actualizar
                    </flux:button>
                </div>
            @endforeach

            <div class="flex items-center gap-3 mt-5">
                <flux:input type="email" placeholder="Nuevo correo" wire:model.defer="nuevoCorreo" class="flex-1" />
                <flux:button variant="primary" wire:click="agregarCorreo">
                    Agregar
                </flux:button>
            </div>
        </div>

        <!-- Teléfonos -->
        <div class="p-4 rounded-md shadow">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Teléfonos</h2>

            @foreach ($telefonos as $index => $telefono)
                <div class="flex items-center gap-3 mb-3">
                    <flux:input type="text" wire:model.defer="telefonos.{{ $index }}.numero" class="flex-1" />
                    <flux:button wire:click="actualizarTelefono({{ $index }})">
                        Actualizar
                    </flux:button>
                </div>
            @endforeach

            <div class="flex items-center gap-3 mt-5">
                <flux:input type="text" placeholder="Nuevo teléfono" wire:model.defer="nuevoTelefono" class="flex-1" />
                <flux:button variant="primary" wire:click="agregarTelefono">
                    Agregar
                </flux:button>
            </div>
        </div>
    </div>
</div>

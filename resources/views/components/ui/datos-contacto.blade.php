<div class="mb-3 py-3">
    <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos de Contacto</h1>
</div>

<flux:modal.trigger name="edit-contactos">
    <flux:button>Datos de Contacto</flux:button>
</flux:modal.trigger>

@if ($errors->has('correos') || $errors->has('telefonos'))
<p class="text-red-500 text-sm mt-1">Hace falta agregar al menos un correo o teléfono.</p>
@endif

<flux:modal name="edit-contactos" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Datos de contacto</flux:heading>
        </div>

        <!-- Correos electrónicos -->
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Correos electrónicos
                @if ($prefix === 'solicitante')
                *
                @endif
            </label>


            <div class="flex gap-2">
                <flux:input oninput="this.value = this.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                    wire:model.defer="correo_temp" type="email" placeholder="Agregar correo" />
                <x-boton-agregar wire-click="agregarCorreo" />
            </div>

            @if (!empty($correos))
            <ul class="mt-2 space-y-1">
                @foreach ($correos as $i => $correo)
                <li
                    class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                    <span class="truncate">{{ $correo }}</span>
                    <button wire:click="eliminarCorreo({{ $i }})"
                        class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                        title="Eliminar">
                        ×
                    </button>
                </li>
                @endforeach
            </ul>
            @endif

        </div>

        <!-- Teléfonos -->
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Teléfonos
                @if ($prefix === 'solicitante')
                *
                @endif
            </label>

            <div class="flex gap-2">
                <flux:input wire:model.defer="telefono_temp" type="tel" placeholder="Agregar teléfono" inputmode="tel"
                    maxlength="10" oninput="this.value = this.value.replace(/\D+/g,'').slice(0,10)" />
                <x-boton-agregar wire-click="agregarTelefono" />

            </div>

            @if (!empty($telefonos))
            <ul class="mt-2 space-y-1">
                @foreach ($telefonos as $i => $tel)
                <li
                    class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                    <span class="truncate">{{ $tel }}</span>
                    <button wire:click="eliminarTelefono({{ $i }})"
                        class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                        title="Eliminar">
                        ×
                    </button>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="flex">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost">Cerrar</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
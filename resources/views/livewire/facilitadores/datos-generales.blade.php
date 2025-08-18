<div>
<div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2 "> 
     
        <flux:input
            wire:model="nombre"
            :label="__('Nombre de la persona facilitadora')"
            type="text"
            required
            placeholder="Nombre de la persona facilitadora"
        />
    
        <flux:input
            wire:model="clave"
            :label="__('Clave o número de registro')"
            type="text"
            required
            placeholder="Clave o número de registro de certificación del Sistema Nacional"
        />
    
        <flux:input
            wire:model="folio"
            :label="__('Folio único de registro')"
            type="text"
            required
            placeholder="Folio único de registro como persona facilitadora certificada"
        />
    
        <flux:input
            wire:model="clave_unica"
            :label="__('Clave Única de Registro de Población')"
            type="text"
            required
            placeholder="Clave Única de Registro de Población"
        />
     
        <flux:input
            wire:model="fecha_certificacion"
            :label="__('Fecha de certificación')"
            type="date"
            required
            placeholder="Fecha de certificación de la persona facilitadora"
        />
    
        <flux:input
            wire:model="vigencia_certificacion"
            :label="__('Vigencia de la certificación')"
            type="date"
            required
            placeholder="Vigencia de la certificación de la persona facilitadora"
        />
    </div>

    <div class="grid grid-cols-2 gap-4 mt-2">
         <!-- Correos electrónicos -->
         <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Correos electrónicos
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>

            <div class="flex gap-2">
                <flux:input
                    oninput="this.value = this.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                    wire:model.defer="correo_temp"
                    type="email"
                    placeholder="Agregar correo"
                />
                <button
                    type="button"
                    wire:click="agregarCorreo"
                    class="px-3 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700"
                >
                    Agregar
                </button>
            </div>

            @if (!empty($correos))
                <ul class="mt-2 space-y-1">
                    @foreach ($correos as $i => $correo)
                        <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                            <span class="truncate">{{ $correo }}</span>
                            <button
                                wire:click="eliminarCorreo({{ $i }})"
                                class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                                title="Eliminar"
                            >
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
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>

            <div class="flex gap-2">
                <flux:input
                    oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                    wire:model.defer="telefono_temp"
                    type="tel"
                    maxlength ="10"
                    placeholder="Agregar teléfono"
                />
                <button
                    type="button"
                    wire:click="agregarTelefono"
                    class="px-3 py-2 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700"
                >
                    Agregar
                </button>
            </div>

            @if (!empty($telefonos))
                <ul class="mt-2 space-y-1">
                    @foreach ($telefonos as $i => $tel)
                        <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                            <span class="truncate">{{ $tel }}</span>
                            <button
                                wire:click="eliminarTelefono({{ $i }})"
                                class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded"
                                title="Eliminar"
                            >
                                ×
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="mb-3 py-3">
        <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos domicilio</h1>
    </div>
    

    <div class=" grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Tipo domicilio
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio...">
                <flux:select.option>Casa</flux:select.option>
                <flux:select.option>Oficina</flux:select.option>
                <flux:select.option>Otro</flux:select.option>
            </flux:select>
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Calle
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="calle_solicitante"
                type="text"
                required
                placeholder="Calle"
            />
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Código Postal
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:input
                wire:model.live="cp_solicitante"
                maxlength="5"
                type="text"
                required
                placeholder="Código postal"
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
            />
        </div>
        
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Colonia
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:select wire:model="colonia" placeholder="Selecciona una colonia...">
                @foreach ($colonias as $col)
                    <flux:select.option>{{ $col->colonia }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>    
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Municipio
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:input
                wire:model="municipio_solicitante"
                type="text"
                readonly
                placeholder="Municipio"
            />
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Entidad Federativa
                
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                
            </label>
            <flux:input
                wire:model="entidad_federativa_solicitante"
                type="text"
                readonly
                placeholder="Entidad federativa"
            />
        </div>
    </div>

    <div class="mt-5 mb-2  text col-span-3">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Fotografía
            <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
        </label>
    
        <livewire:dropzone
            wire:model="fotografia"
            :rules="['mimes:jpg,png','max:10420']"
            :multiple="false" />
    </div>
</div>

</div>

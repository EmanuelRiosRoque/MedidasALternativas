@props(['prefix'])
<div class=" gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}"> 

    <div class="grid grid-cols-3 gap-4">
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Nombre
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="nombre_solicitante"
                type="text"
                required
                placeholder="Nombre"
            />
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Apellido paterno 
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                wire:model="apellido_p_solicitante"
                type="text"
                required
                placeholder="Apellido paterno"
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
            />
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Apellido materno 
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                wire:model="apellido_m_solicitante"
                type="text"
                required
                placeholder="Apellido materno"
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
            />
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                RFC
                    {{-- <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge> --}}
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="rfc_solicitante"
                type="text"
                required
                placeholder="RFC"
            />
        </div>
    
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Sexo
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo...">
                <flux:select.option>Femenino</flux:select.option>
                <flux:select.option>Masculino</flux:select.option>
            </flux:select>
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Edad 
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="edad_solicitante"
                type="text"
                required
                placeholder="Edad"
            />
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Fecha de nacimiento
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="fecha_nacimiento_solicitante"
                type="date"
                required
            />
        </div>
    
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Escolaridad
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:select wire:model="escolaridad_solicitante" placeholder="Elige escolaridad...">
                @foreach ($escolaridades as $escolaridad)
                    <flux:select.option>{{ $escolaridad }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Ocupación
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>

                <flux:select wire:model="ocupacion_solicitante" placeholder="Elige Ocupació...">
                    @foreach ($ocupaciones as $ocupacion)
                        <flux:select.option>{{ $ocupacion }}</flux:select.option>
                    @endforeach
                </flux:select> 
        </div>
    
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Nacionalidad
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:select wire:model="nacionalidad_solicitante" placeholder="Elige tipo domicilio...">
                <flux:select.option>Méxicana</flux:select.option>
                <flux:select.option>Extranjera</flux:select.option>
            </flux:select>
        </div>

     
      
    <!-- Correos electrónicos -->
    {{-- <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Correos electrónicos
            @if (!($modalidad === 'presencial' && $prefix === 'invitado'))
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>

        <div class="flex gap-2">
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
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
    </div> --}}
        
    </div>

    <div class="mb-3 py-3">
        <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos de Contacto</h1>
    </div>

    <flux:modal.trigger name="edit-profile">
        <flux:button>Datos de Contacto</flux:button>
    </flux:modal.trigger>

    <flux:modal name="edit-profile" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Datos de contacto</flux:heading>
            </div>

             <!-- Correos electrónicos -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Correos electrónicos
                    @if (!($modalidad === 'presencial' && $prefix === 'invitado'))
                        <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                    @endif

                </label>

                <div class="flex gap-2">
                    <flux:input
                        oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                        wire:model.defer="correo_temp"
                        type="email"
                        placeholder="Agregar correo"
                    />
                    <x-boton-agregar wire-click="agregarCorreo" />
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
                        placeholder="Agregar teléfono"
                    />                    
                    <x-boton-agregar wire-click="agregarTelefono" />

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

            <div class="flex">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cerrar</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

   
    <div class="mb-3 py-3">
        <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos domicilio</h1>
    </div>
    

    <div class=" grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Tipo domicilio
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
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
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
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
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
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
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
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
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
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
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                @endif
            </label>
            <flux:input
                wire:model="entidad_federativa_solicitante"
                type="text"
                readonly
                placeholder="Entidad federativa"
            />
        </div>
    </div>
</div>

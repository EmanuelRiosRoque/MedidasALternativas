@props(['prefix'])

<div class="gap-4 mt-2 animate__animated animate__fadeIn" wire:key='{{ $key }}'> 

    <div class="grid grid-cols-3 gap-4">
 {{-- Nombre --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Nombre 
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="nombre_solicitante"
            type="text"
            required
            placeholder="Nombre"
            oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
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
            oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
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
            oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
        />
    </div>
    {{-- Edad --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Edad 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="edad_solicitante"
            type="text"
            required
            placeholder="Edad "
            oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
        />
    </div>

     {{-- Sexo --}}
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

    {{-- Escolaridad --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Escolaridad 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="escolaridad_solicitante" placeholder="Elige escolaridad ...">
            @foreach ($escolaridades as $escolaridad)
            <flux:select.option>
                {{ $escolaridad }}
            </flux:select.option>
            @endforeach
        </flux:select>
    </div>

    {{-- Ocupación --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Ocupación 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="ocupacion_solicitante" placeholder="Elige ocupación...">
            @foreach ($ocupaciones as $ocupacion)
                <flux:select.option>{{ $ocupacion }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

   
    {{-- Estado civil --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Estado civil 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="estado_civil_solicitante" placeholder="Elige estado civil  ...">
            <flux:select.option>Soltero</flux:select.option>
            <flux:select.option>Casado</flux:select.option>
            <flux:select.option>Unión libre</flux:select.option>
            <flux:select.option>Separado</flux:select.option>
            <flux:select.option>Divorciado</flux:select.option>
            <flux:select.option>Viudo</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>        
    </div>

    </div>

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
                    @if (!($modalidad === 'presencial' && $prefix === 'invitado'))
                        <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                    @endif

                </label>

                <div class="flex gap-2">
                    <flux:input
                        oninput="this.value = this.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
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
                        oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
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
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
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
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
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
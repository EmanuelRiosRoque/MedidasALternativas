<div>

    <div class=" grid grid-cols-4 gap-4 mb-4">
        <flux:radio.group wire:model="tipo" label="Persona facilitadora *">
            <flux:radio value="1" label="Público" />
            <flux:radio value="2" label="Privado" />
        </flux:radio.group>
    
        <flux:radio.group wire:model="materia" label="Materias Certificado *">
            <flux:radio value="1" label="Civil-Mercantil " />
            <flux:radio value="2" label="Familiar" />
            <flux:radio value="3" label="Ambas" />
        </flux:radio.group>
    
        <flux:radio.group wire:model="estudios" label="Grado de estudios *">
            <flux:radio value="1" label="Licenciatura" />
            <flux:radio value="2" label="Maestría" />
            <flux:radio value="3" label="Doctorado" />
        </flux:radio.group>
    
        <flux:input 
            wire:model="cedula" 
            :label="__('Núm.Cédula Profesional *')" 
            type="text"
            placeholder="Número de Cédula Profesional, expedida por la Dirección General de Profesiones" 
        />
    </div>

    <div x-show="tipo !== ''" x-cloak class="mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2 "> 
            <flux:input
                wire:model="nombre"
                :label="__('Nombre *')"
                type="text"
                required
                placeholder="Nombre de la persona facilitadora"
            />

            <flux:input
                wire:model="clave_certificacion"
                :label="__('Clave o número de certificación del Poder Judicial que la otorgó *')"
                type="text"
                required
                placeholder="Clave o número de certificación del Poder Judicial que la otorgó"
            />
        
            <flux:input
                wire:model="folio"
                :label="__('Folio único de registro *')"
                type="text"
                required
                placeholder="Folio único de registro como persona facilitadora certificada"
            />
        
            <flux:input
                wire:model="clave_unica"
                :label="__('Clave Única de Registro de Población *')"
                type="text"
                required
                placeholder="Clave Única de Registro de Población"
            />
        
            <flux:input
                wire:model="fecha_certificacion"
                :label="__('Fecha de certificación inicial *')"
                type="date"
                required
                placeholder="Fecha de certificación inicial"
            />
        
            <flux:input
                wire:model="vigencia_certificacion "
                :label="__('Vigencia de la certificación *')"
                type="date"
                required
                placeholder="Periodo de vigencia de certificación "
            />
        </div>

        <!-- Seccion: datos de contacto correo y telefono-->
        <div class="grid grid-cols-2 gap-4 mt-2">
            <!-- Correos electrónicos -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Correos electrónicos *
                </label>

                <div class="flex gap-2">
                    <flux:input
                        oninput="this.value = this.value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                        wire:model.defer="correo_temp"
                        type="email"
                        placeholder="Agregar correo"
                    />

                    <!-- Flux Select etiqueta correo -->
                    <flux:select 
                        wire:model.defer="correo_etiqueta_temp" 
                        placeholder="Etiqueta"
                        class="max-w-fit"
                    >
                        <flux:select.option value="1">Laboral</flux:select.option>
                        <flux:select.option value="2">Personal</flux:select.option>
                    </flux:select>



                    <flux:button variant="primary" wire:click="agregarCorreo">
                        Agregar
                    </flux:button>
                </div>

                @if (!empty($correos))
                    <ul class="mt-2 space-y-1">
                        @foreach ($correos as $i => $correo)
                            <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                                <span class="truncate">
                                    {{ $correo['direccion'] }}
                                    <span class="ml-2 text-xs text-zinc-500">
                                        [{{ $correo['tipo'] == 1 ? 'Laboral' : ($correo['tipo'] == 2 ? 'Personal' : 'Sin etiqueta') }}]
                                    </span>
                                </span>
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
                    Teléfonos *
                </label>

                <div class="flex gap-2">
                    <flux:input
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)" 
                        wire:model.defer="telefono_temp"
                        type="tel"
                        maxlength="10"
                        placeholder="Agregar teléfono"
                    />


                    <!-- Flux Select etiqueta teléfono -->
                    <flux:select 
                        wire:model.defer="telefono_etiqueta_temp"  
                        placeholder="Etiqueta"
                        class="max-w-fit"
                    >
                        <flux:select.option value="1">Laboral</flux:select.option>
                        <flux:select.option value="2">Personal</flux:select.option>
                    </flux:select>

                    <flux:button variant="primary" wire:click="agregarTelefono">
                        Agregar
                    </flux:button>
                </div>

                @if (!empty($telefonos))
                    <ul class="mt-2 space-y-1">
                        @foreach ($telefonos as $i => $tel)
                            <li class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
                                <span class="truncate">
                                    {{ $tel['numero'] }}
                                    <span class="ml-2 text-xs text-zinc-500">
                                        [{{ $tel['tipo'] == 1 ? 'Laboral' : ($tel['tipo'] == 2 ? 'Personal' : 'Sin etiqueta') }}]
                                    </span>
                                </span>
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


        <!-- Seccion: documucio -->
        <div class="mb-3 py-3">
            <h1 class="text-xl border-b-2 border-emerald-700 inline-block pb-1">Datos domicilio</h1>
        </div> 

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Tipo domicilio *                
                </label>
                <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio...">
                    <flux:select.option>Casa</flux:select.option>
                    <flux:select.option>Oficina</flux:select.option>
                    <flux:select.option>Otro</flux:select.option>
                </flux:select>
            </div>
        
            <div class="space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Calle *                
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
                    Código Postal *                
                </label>
                <flux:input
                    wire:model.live="cp_solicitante"
                    maxlength="5"
                    type="number"
                    required
                    placeholder="Código postal"
                    oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                />
            </div>
            
            <div class="space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Colonia *                
                </label>
                <flux:select wire:model="colonia" placeholder="Selecciona una colonia...">
                    @foreach ($colonias as $col)
                        <flux:select.option>{{ $col->colonia }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>    
        
            <div class="space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Municipio *                
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
                    Entidad Federativa *                
                </label>
                <flux:input
                    wire:model="entidad_federativa_solicitante"
                    type="text"
                    readonly
                    placeholder="Entidad federativa"
                />
            </div>
        </div>

        <!-- Seccion: Fotografia-->
        <div class="mt-5 mb-2  text col-span-3">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Fotografía *
            </label>
        
            <livewire:dropzone
                wire:model="fotografia"
                :rules="['mimes:jpg,png','max:10420']"
                :multiple="false" />
        </div>
    </div>
    
</div>

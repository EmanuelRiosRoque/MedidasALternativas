<div>
    @if ($personaSeleccionada['persona'] === 'fisica')
    <div class="grid grid-cols-3 gap-3">
        <flux:input 
            wire:model="personaSeleccionada.nombre"
            label="Nombre" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.apellido_p"
            label="Apellido paterno" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.apellido_m"
            label="Apellido materno" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:field>
            <flux:label>Sexo</flux:label>
            <flux:select wire:model="personaSeleccionada.sexo" placeholder="Seleccione...">
                <flux:select.option>Femenino</flux:select.option>
                <flux:select.option>Masculino</flux:select.option>
            </flux:select>
        </flux:field>

        <flux:input 
            wire:model="personaSeleccionada.edad"
            label="Edad" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.fecha_nacimiento" 
            label="Fecha de nacimiento" 
            type="date" 
        />

        <flux:field>
            <flux:label>Escolaridad</flux:label>
            <flux:select wire:model="personaSeleccionada.escolaridad" placeholder="Seleccione...">
                @foreach ($escolaridades as $escolaridad)
                <flux:select.option value="{{ $escolaridad->id }}">{{ $escolaridad->nombre }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Ocupación</flux:label>
            <flux:select wire:model="personaSeleccionada.ocupacion" placeholder="Seleccione...">
                @foreach ($ocupaciones as $ocupacion)
                <flux:select.option value="{{ $ocupacion->id }}">{{ $ocupacion->nombre }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Nacionalidad</flux:label>
            <flux:select wire:model="personaSeleccionada.nacionalidad" placeholder="Seleccione...">
                <flux:select.option value="1">Méxicana</flux:select.option>
                <flux:select.option value="2">Extranjera</flux:select.option>
            </flux:select>
        </flux:field>
    </div>
    @endif

    @if ($personaSeleccionada['persona'] === 'moral')
    <div class="grid grid-cols-2 gap-3">
        <flux:input 
            wire:model="personaSeleccionada.razon_social"
            label="Razon social" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.rfc"
            label="RFC" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.instrumento"
            label="Instrumento de razon social" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.fecha_instrumento" 
            label="Fecha del instrumento" 
            type="date" 
        />
    </div>
    @endif


    @if ($personaSeleccionada['persona'] === 'familiar')
    <div class="grid grid-cols-3 gap-3">
        <flux:input 
            wire:model="personaSeleccionada.nombre"
            label="Nombre" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.apellido_p"
            label="Apellido paterno" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.apellido_m"
            label="Apellido materno" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:field>
            <flux:label>Sexo</flux:label>
            <flux:select wire:model="personaSeleccionada.sexo" placeholder="Seleccione...">
                <flux:select.option value="1">Femenino</flux:select.option>
                <flux:select.option value="2">Masculino</flux:select.option>
            </flux:select>
        </flux:field>

        <flux:input 
            wire:model="personaSeleccionada.edad"
            label="Edad" 
            oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
        />

        <flux:input 
            wire:model="personaSeleccionada.fecha_nacimiento" 
            label="Fecha de nacimiento" 
            type="date" 
        />

        <flux:field>
            <flux:label>Escolaridad</flux:label>
            <flux:select wire:model="personaSeleccionada.escolaridad" placeholder="Seleccione...">
                @foreach ($escolaridades as $escolaridad)
                <flux:select.option value="{{ $escolaridad->id }}">{{ $escolaridad->nombre }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Ocupación</flux:label>
            <flux:select wire:model="personaSeleccionada.ocupacion" placeholder="Seleccione...">
                @foreach ($ocupaciones as $ocupacion)
                <flux:select.option value="{{ $ocupacion->id }}">{{ $ocupacion->nombre }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:field>

        <flux:field>
            <flux:label>Nacionalidad</flux:label>
            <flux:select wire:model="personaSeleccionada.nacionalidad" placeholder="Seleccione...">
                <flux:select.option value="1">Méxicana</flux:select.option>
                <flux:select.option value="2">Extranjera</flux:select.option>
            </flux:select>
        </flux:field>
    </div>
    @endif

    <div>   
        <h1 class="text-base font-semibold uppercase text-emerald-400 tracking-wider mb-4 mt-4">
            Datos domicilio
        </h1>

        <div class="grid grid-cols-3 gap-3">
            <flux:field>
                <flux:label>Tipo domicilio</flux:label>
                <flux:select wire:model="personaSeleccionada.tipo_domicilio" placeholder="Seleccione...">
                    <flux:select.option>Casa</flux:select.option>
                    <flux:select.option>Oficina</flux:select.option>
                    <flux:select.option>Otro</flux:select.option>
                </flux:select>
            </flux:field>

            <flux:input 
                wire:model.live.debounce.250ms="personaSeleccionada.calle" 
                label="Calle"
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
            />

            <flux:input 
                wire:model.lazy="personaSeleccionada.cp" 
                label="Código postal" 
                maxlength="5"
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
            />

            <flux:field>
                <flux:label>Colonias</flux:label>
                <flux:select wire:model="personaSeleccionada.colonia" placeholder="Seleccione...">
                    <flux:select.option value="">--Seleccione--</flux:select.option>
                    @foreach ($colonias as $col)
                    <flux:select.option>{{ $col->colonia }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:input 
                wire:model="personaSeleccionada.municipio" 
                label="Municipio" 
                readonly
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
            />

            <flux:input 
                wire:model="personaSeleccionada.entidad_federativa" 
                label="Entidad Federativa" 
                readonly
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')" 
            />
        </div>
    </div>

    <div class="mt-5 mb-5">
        <flux:button variant="primary" class="w-full" wire:click="actualizarDatos">
            Actualizar
        </flux:button>
    </div>
</div>
@props(['prefix'])
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}"> 

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Nombre
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="nombre_solicitante"
            type="text"
            required
            placeholder="Nombre"
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
            oninput="this.value = this.value.toUpperCase()"
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
            oninput="this.value = this.value.toUpperCase()"
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

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Ocupación
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
            <flux:select wire:model="ocupacion_solicitante" placeholder="Elige escolaridad...">
                @foreach ($ocupaciones as $ocupacion)
                    <flux:select.option>{{ $ocupacion }}</flux:select.option>
                @endforeach
            </flux:select>
            
        </label>
        
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
            oninput="this.value = this.value.toUpperCase()"
            wire:model="calle_solicitante"
            type="text"
            required
            placeholder="Calle"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Colonia
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="colonia_solicitante"
            type="text"
            required
            placeholder="Colonia"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Código postal 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="cp_solicitante"
            type="text"
            required
            placeholder="Código postal"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Municipio 
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="municipio_solicitante"
            type="text"
            required
            placeholder="Municipio"
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Entidad federativa
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="entidad_federativa_solicitante" placeholder="Elige entidad federativa...">
            @foreach ($entidades as $entidad)
                <flux:select.option>{{ $entidad }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Correo electrónico
            @if (
                ($modalidad === 'presencial') ||
                ($modalidad === 'linea' && $prefix === 'solicitante')
            )
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        
        
        
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="correo_solicitante"
            type="email"
            required
            placeholder="Correo electrónico de {{ $prefix }}"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Teléfono del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="telefono_solicitante"
            type="tel"
            required
            placeholder="Teléfono del {{ $prefix }}"
        />
    </div>

</div>

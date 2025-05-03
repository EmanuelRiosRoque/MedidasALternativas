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
            placeholder="Nombre de {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>
    {{-- Edad --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Edad 
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="edad_solicitante"
            type="text"
            required
            placeholder="Edad "
            oninput="this.value = this.value.toUpperCase()"
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
        <flux:select wire:model="ocupacion_solicitante" placeholder="Elige ocupacion...">
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

    {{-- Correo --}}
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
            wire:model="correo_solicitante"
            type="text"
            required
            placeholder="Correo electrónico"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    </div>

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
                oninput="this.value = this.value.toUpperCase()"
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
                oninput="this.value = this.value.toUpperCase()"
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
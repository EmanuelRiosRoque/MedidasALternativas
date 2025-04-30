@props(['prefix'])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key='{{ $key }}'> 

    {{-- Nombre --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Nombre del {{ $prefix }}
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

    {{-- Calle --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Calle del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="calle_solicitante"
            type="text"
            required
            placeholder="Calle del {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    {{-- Colonia --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Colonia del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="colonia_solicitante"
            type="text"
            required
            placeholder="Colonia del {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    {{-- CP --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Código postal del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="cp_solicitante"
            type="text"
            required
            placeholder="Código postal del {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    {{-- Municipio --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Municipio del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="municipio_solicitante"
            type="text"
            required
            placeholder="Municipio del {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    {{-- Entidad federativa --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Entidad federativa
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:select wire:model="entidad_federativa_solicitante" placeholder="Elige entidad federativa del {{ $prefix }}...">
            @foreach ($entidades as $entidad)
                <flux:select.option>{{ $entidad }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    {{-- Edad --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Edad del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="edad_solicitante"
            type="text"
            required
            placeholder="Edad del {{ $prefix }}"
            oninput="this.value = this.value.toUpperCase()"
        />
    </div>

    {{-- Escolaridad --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Escolaridad del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="escolaridad_solicitante" placeholder="Elige escolaridad del {{ $prefix }}...">
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
            Ocupación del {{ $prefix }}
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

    {{-- Sexo --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Sexo del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo...">
            <flux:select.option>Femenino</flux:select.option>
            <flux:select.option>Masculino</flux:select.option>
        </flux:select>
    </div>

    {{-- Estado civil --}}
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Estado civil del {{ $prefix }}
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
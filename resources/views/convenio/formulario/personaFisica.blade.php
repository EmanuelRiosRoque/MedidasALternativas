@props(['prefix'])
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}"> 

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
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Sexo del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo del {{ $prefix }}...">
            <flux:select.option>Femenino</flux:select.option>
            <flux:select.option>Masculino</flux:select.option>
        </flux:select>
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Edad del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="edad_solicitante"
            type="text"
            required
            placeholder="Edad del {{ $prefix }}"
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
            wire:model="fecha_nacimiento_solicitante"
            type="date"
            required
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Escolaridad del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="escolaridad_solicitante" placeholder="Elige escolaridad del {{ $prefix }}...">
            <flux:select.option>Sin escolaridad</flux:select.option>
            <flux:select.option>Primaria</flux:select.option>
            <flux:select.option>Secundaria</flux:select.option>
            <flux:select.option>Media Superior</flux:select.option>
            <flux:select.option>Superior</flux:select.option>
            <flux:select.option>Posgrado</flux:select.option>
        </flux:select>
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Ocupación del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="ocupacion_solicitante"
            type="text"
            required
            placeholder="Ocupación del {{ $prefix }}"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Nacionalidad del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="nacionalidad_solicitante"
            type="text"
            required
            placeholder="Nacionalidad del {{ $prefix }}"
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Tipo domicilio
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio del {{ $prefix }}...">
            <flux:select.option>Casa</flux:select.option>
            <flux:select.option>Oficina</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Calle del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="calle_solicitante"
            type="text"
            required
            placeholder="Calle del {{ $prefix }}"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Colonia del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="colonia_solicitante"
            type="text"
            required
            placeholder="Colonia del {{ $prefix }}"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Código postal del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="cp_solicitante"
            type="text"
            required
            placeholder="Código postal del {{ $prefix }}"
        />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Municipio del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="municipio_solicitante"
            type="text"
            required
            placeholder="Municipio del {{ $prefix }}"
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Entidad federativa
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:select wire:model="entidad_federativa_solicitante" placeholder="Elige entidad federativa del {{ $prefix }}...">
            @foreach ($entidades as $entidad)
                <flux:select.option>{{ $entidad }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Correo electrónico del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="correo_solicitante"
            type="email"
            required
            placeholder="Correo electrónico de {{ $prefix }}"
        />
    </div>

</div>

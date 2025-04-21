@props(['prefix'])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}">

    {{-- Razón social --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Razón social del {{ $prefix }}
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="razon_social_solicitante"
            type="text"
            required
            placeholder="Razón social de {{ $prefix }}"
        />
    </div>

    {{-- RFC --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            RFC
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            wire:model="rfc_solicitante"
            type="text"
            required
            placeholder="RFC"
        />
    </div>

    {{-- Instrumento notarial --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Instrumento notarial
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="instrumento_solicitante"
            type="text"
            required
            placeholder="Instrumento notarial"
        />
    </div>

    {{-- Fecha del instrumento --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Fecha del Instrumento Notarial
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="fecha_instrumento_solicitante"
            type="date"
            required
        />
    </div>

    {{-- Teléfono --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Teléfono del {{ $prefix }}
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            wire:model="telefono_solicitante"
            type="tel"
            required
            placeholder="Teléfono del {{ $prefix }}"
        />
    </div>

    {{-- Calle --}}
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

    {{-- Colonia --}}
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

    {{-- CP --}}
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

    {{-- Municipio --}}
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

    {{-- Entidad federativa --}}
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

    {{-- Correo --}}
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
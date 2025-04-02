@props(['prefix' => 'solicitante'])

    <flux:input
        wire:model="razon_social_{{ $prefix }}"
        :label="__('Razon social del '. $prefix)"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    <flux:input
        wire:model="rfc_{{ $prefix }}"
        :label="__('RFC')"
        type="text"
        required
        placeholder="RFC"
    />

    <flux:input
        wire:model="instrumento_{{ $prefix }}"
        :label="__('Instrumento notarial')"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    
    <flux:input
        wire:model="fecha_instrumento_{{ $prefix }}"
        :label="__('Fecha del Instrumento Notarial')"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    <flux:input
        wire:model="telefono_{{ $prefix }}"
        :label="__('Fecha del Instrumento Notarial')"
        type="tel"
        required
        placeholder="Razon social de {{ $prefix }}"
    />


    <flux:input
        wire:model="calle_{{ $prefix }}"
        :label="__('Calle')"
        type="text"
        required
        placeholder="Calle del {{ $prefix }}"
    />

    <flux:input
        wire:model="colonia_{{ $prefix }}"
        :label="__('Colonia')"
        type="text"
        required
        placeholder="Colonia del {{ $prefix }}"
    />

    <flux:input
        wire:model="municipio_{{ $prefix }}"
        :label="__('Municipio')"
        type="text"
        required
        placeholder="Municipio del {{ $prefix }}"
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Entidad federativa
        </label>
        <flux:select wire:model="entidad_federativa_{{ $prefix }}" placeholder="Elige entidad federativa del {{ $prefix }}...">
            <flux:select.option>Casa</flux:select.option>
            <flux:select.option>Oficina</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>
    </div>


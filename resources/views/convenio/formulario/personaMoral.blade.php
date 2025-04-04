@props(['prefix' => 'solicitante'])
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}"> 

    <flux:input
        wire:model="razon_social_solicitante"
        :label="__('Razon social del '. $prefix)"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    <flux:input
        wire:model="rfc_solicitante"
        :label="__('RFC')"
        type="text"
        required
        placeholder="RFC"
    />

    <flux:input
        wire:model="instrumento_solicitante"
        :label="__('Instrumento notarial')"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    
    <flux:input
        wire:model="fecha_instrumento_solicitante"
        :label="__('Fecha del Instrumento Notarial')"
        type="text"
        required
        placeholder="Razon social de {{ $prefix }}"
    />

    <flux:input
        wire:model="telefono_solicitante"
        :label="__('Telefono')"
        type="tel"
        required
        placeholder="Telefono {{ $prefix }}"
    />


    <flux:input
        wire:model="calle_solicitante"
        :label="__('Calle')"
        type="text"
        required
        placeholder="Calle del {{ $prefix }}"
    />

    <flux:input
        wire:model="colonia_solicitante"
        :label="__('Colonia')"
        type="text"
        required
        placeholder="Colonia del {{ $prefix }}"
    />

    <flux:input
        wire:model="municipio_solicitante"
        :label="__('Municipio')"
        type="text"
        required
        placeholder="Municipio del {{ $prefix }}"
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Entidad federativa
        </label>
        <flux:select wire:model="entidad_federativa_solicitante" placeholder="Elige entidad federativa del {{ $prefix }}...">
            <flux:select.option>Casa</flux:select.option>
            <flux:select.option>Oficina</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>
    </div>

    <flux:input
        wire:model="correo_solicitante"
        :label="__('Correo electronico del '. $prefix )"
        type="email"
        required
        placeholder="Correo electronico de {{ $prefix }}"
    />
</div>
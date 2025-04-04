@props(['prefix' => 'solicitante'])
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}"> 

    <flux:input
        wire:model="nombre_solicitante"
        :label="__('Nombre del '. $prefix )"
        type="text"
        required
        placeholder="Nombre de {{ $prefix }}"
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Sexo del {{ $prefix }}
        </label>
        <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo del {{ $prefix }}...">
            <flux:select.option>Femenino</flux:select.option>
            <flux:select.option>Masculino</flux:select.option>
        </flux:select>
    </div>

    <flux:input
        wire:model="edad_solicitante"
        :label="__('Edad del '. $prefix )"
        type="text"
        required
        placeholder="Edad del {{ $prefix }}"
    />

    <flux:input
        wire:model="fecha_nacimiento_solicitante"
        :label="__('Fecha de nacimiento')"
        type="date"
        required
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Escolaridad del {{ $prefix }}
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

    <flux:input
        wire:model="ocupacion_solicitante"
        :label="__('Ocupacion')"
        type="text"
        required
        placeholder="Ocupacion del {{ $prefix }}"
    />

    <flux:input
        wire:model="nacionalidad_solicitante"
        :label="__('Nacionalidad')"
        type="text"
        required
        placeholder="Nacionalidad del {{ $prefix }}"
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Tipo domicilio
        </label>
        <flux:select wire:model="tipo_domicilio_solicitante" placeholder="Elige tipo domicilio del {{ $prefix }}...">
            <flux:select.option>Casa</flux:select.option>
            <flux:select.option>Oficina</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>
    </div>

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
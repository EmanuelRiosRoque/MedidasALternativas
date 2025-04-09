@props(['prefix' => 'solicitante'])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn"> 

    <flux:input
        wire:model="nombre_{{ $prefix }}"
        :label="__('Nombre del '. $prefix )"
        type="text"
        required
        placeholder="Nombre de {{ $prefix }}"
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
        wire:model="cp_solicitante"
        :label="__('CP')"
        type="text"
        required
        placeholder="Codigo postal del {{ $prefix }}"
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
            <flux:select.option>Entidad 1</flux:select.option>
            <flux:select.option>Entidad 2</flux:select.option>
            <flux:select.option>Entidad 3</flux:select.option>
        </flux:select>
    </div>


    <flux:input
        wire:model="edad_{{ $prefix }}"
        :label="__('Edad del '. $prefix )"
        type="text"
        required
        placeholder="Edad del {{ $prefix }}"
    />

    
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Escolaridad del {{ $prefix }}
        </label>
        <flux:select wire:model="escolaridad_{{ $prefix }}" placeholder="Elige escolaridad del {{ $prefix }}...">
            <flux:select.option>Sin escolaridad</flux:select.option>
            <flux:select.option>Primaria</flux:select.option>
            <flux:select.option>Secundaria</flux:select.option>
            <flux:select.option>Media Superior</flux:select.option>
            <flux:select.option>Superior</flux:select.option>
            <flux:select.option>Posgrado</flux:select.option>
        </flux:select>
    </div>

    <flux:input
        wire:model="ocupacion_{{ $prefix }}"
        :label="__('Ocupacion')"
        type="text"
        required
        placeholder="Ocupacion del {{ $prefix }}"
    />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Sexo del {{ $prefix }}
        </label>
        <flux:select wire:model="sexo_{{ $prefix }}" placeholder="Elige sexo del {{ $prefix }}...">
            <flux:select.option>Femenino</flux:select.option>
            <flux:select.option>Masculino</flux:select.option>
        </flux:select>
    </div>


    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Estado Civil
        </label>
        <flux:select wire:model="estado_civil_{{ $prefix }}" placeholder="Elige estado civil del {{ $prefix }}...">
            <flux:select.option>Soltero</flux:select.option>
            <flux:select.option>Casado</flux:select.option>
            <flux:select.option>Unión libre</flux:select.option>
            <flux:select.option>Separado</flux:select.option>
            <flux:select.option>Divorciado</flux:select.option>
            <flux:select.option>Viudo</flux:select.option>
            <flux:select.option>Otro</flux:select.option>
        </flux:select>        
    </div>

    <flux:input
        wire:model="correo_{{ $prefix }}"
        :label="__('Correo electronico del '. $prefix )"
        type="text"
        required
        placeholder="Correo electronico de {{ $prefix }}"
    />
</div>
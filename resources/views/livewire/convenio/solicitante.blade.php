<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn">

    @if($tipo_persona === 'fisica')
        {{-- Persona Física --}}
        <flux:input wire:model="nombre_solicitante" :label="'Nombre del '.$prefix" type="text" placeholder="Nombre de {{ $prefix }}" />
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Sexo del {{ $prefix }}</label>
            <flux:select wire:model="sexo_solicitante" placeholder="Elige sexo del {{ $prefix }}">
                <flux:select.option>Femenino</flux:select.option>
                <flux:select.option>Masculino</flux:select.option>
            </flux:select>
        </div>
        <flux:input wire:model="edad_solicitante" :label="'Edad del '.$prefix" type="text" />
        <flux:input wire:model="fecha_nacimiento_solicitante" label="Fecha de nacimiento" type="date" />
        <flux:input wire:model="escolaridad_solicitante" label="Escolaridad" type="text" />
        <flux:input wire:model="ocupacion_solicitante" label="Ocupación" type="text" />
        <flux:input wire:model="nacionalidad_solicitante" label="Nacionalidad" type="text" />
        <flux:input wire:model="tipo_domicilio_solicitante" label="Tipo de domicilio" type="text" />
        <flux:input wire:model="calle_solicitante" label="Calle" type="text" />
        <flux:input wire:model="colonia_solicitante" label="Colonia" type="text" />
        <flux:input wire:model="cp_solicitante" label="Código Postal" type="text" />
        <flux:input wire:model="municipio_solicitante" label="Municipio" type="text" />
        <flux:input wire:model="entidad_federativa_solicitante" label="Entidad Federativa" type="text" />
        <flux:input wire:model="correo_solicitante" :label="'Correo electrónico del '.$prefix" type="email" />

    @elseif($tipo_persona === 'moral')
        {{-- Persona Moral --}}
        <flux:input wire:model="razon_social_solicitante" :label="'Razón Social del '.$prefix" type="text" />
        <flux:input wire:model="rfc_solicitante" label="RFC" type="text" />
        <flux:input wire:model="instrumento_solicitante" label="Instrumento Jurídico" type="text" />
        <flux:input wire:model="fecha_instrumento_solicitante" label="Fecha del Instrumento" type="date" />
        <flux:input wire:model="telefono_solicitante" label="Teléfono" type="text" />
    @endif

</div>

@props(['prefix'])

<div class="gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}">


    <div class="grid grid-cols-2 gap-4">
        {{-- Razón social --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Razón social
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="razon_social_solicitante"
            type="text"
            required
            placeholder="Razón social"
        />
    </div>

    {{-- RFC --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            RFC
                {{-- <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge> --}}
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
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
            <flux:tooltip toggleable>
                <flux:button icon="information-circle" size="xs" variant="ghost" />
                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                    <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300">
                        <li>Número de Instrumento Notarial</li>
                        <li>Y</li>
                        <li>Nombre de la Autoridad Fedataria</li>
                    </ul>
                </flux:tooltip.content>
            </flux:tooltip>
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
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
            oninput="this.value = this.value.toUpperCase()"
            wire:model="fecha_instrumento_solicitante"
            type="date"
            required
        />
    </div>

    {{-- Teléfono --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Teléfono
            @if($prefix === 'solicitante')
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            @endif
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="telefono_solicitante"
            type="tel"
            required
            placeholder="Teléfono"
        />
    </div>

    {{-- Correo --}}
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
            Correo electrónico
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
        </label>
        <flux:input
            oninput="this.value = this.value.toUpperCase()"
            wire:model="correo_solicitante"
            type="email"
            required
            placeholder="Correo electrónico"
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
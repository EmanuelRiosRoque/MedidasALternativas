<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    <div class="flex flex-wrap gap-x-6">
        <flux:radio.group wire:model.live="modalidad" label="Modalidad">
            <flux:radio value="presencial" label="Presencial" />
            <flux:radio value="linea" label="En línea" />
        </flux:radio.group>

        <flux:radio.group wire:model.live="materia" label="Materia">
            <flux:radio value="civil" label="Civil" />
            <flux:radio value="mercantil" label="Mercantil" />
            <flux:radio value="familiar" label="Familiar" />
        </flux:radio.group>

        {{-- <flux:radio.group wire:model.live="tipo_convenio" label="Tipo convenio">
            <flux:radio value="publico" label="Publico" />
            <flux:radio value="privado" label="Privado" />
        </flux:radio.group> --}}

        
        <flux:radio.group wire:model.live="derivado_canalizado" label="¿Canalizado?">
            <flux:radio value="1" label="Si" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>
    </div>

    <div>
        @if ($modalidad === 'linea')
            <flux:input wire:model="numero_ticket" :label="__('#Ticket')" type="text" required 
                placeholder="Número de Ticket" />
        @endif
    </div>

    <div>
        @if ($derivado_canalizado == 1)
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Institución
            </label>
            <flux:select wire:model.live="institucion" placeholder="Elige una institución">
                <flux:select.option>Fiscalía</flux:select.option>
                <flux:select.option>Juzgado</flux:select.option>
                <flux:select.option>Comisión de Derechos humanos de la CDMX</flux:select.option>
                <flux:select.option>Secretaría de mujeres. (LUNAS)</flux:select.option>
                <flux:select.option>Juzgado de registro civil</flux:select.option>
                <flux:select.option>Otro</flux:select.option>
            </flux:select>

            <div class="mt-5">
                <flux:heading class="flex items-center gap-1 mb-1">
                    Oficio
                    <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
                </flux:heading>
            
                <livewire:dropzone
                    wire:model="oficio"
                    :rules="['mimes:pdf','max:10420']"
                    :multiple="false"
                    wire:key="oficio"
                    />
            </div>
        @endif
    </div>  
    
    <div>
        @if ($institucion === "Otro")
            <flux:input wire:model="cual_otro" :label="__('Otro:')" type="text" required 
                placeholder="Mencione cuál otro" />
        @endif
    </div>
</div>
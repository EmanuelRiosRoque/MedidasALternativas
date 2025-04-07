<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    <div class="flex flex-wrap gap-x-6">
        <flux:radio.group wire:model.live="modalidad" label="Modalidad">
            <flux:radio value="presencial" label="Presencial" />
            <flux:radio value="linea" label="En línea" />
        </flux:radio.group>

        <flux:radio.group wire:model.live="materia" label="Materia">
            <flux:radio value="mercantil" label="Civil Mercantil" />
            <flux:radio value="familiar" label="Familiar" />
        </flux:radio.group>

        {{-- <flux:radio.group wire:model.live="tipo_convenio" label="Tipo convenio">
            <flux:radio value="publico" label="Publico" />
            <flux:radio value="privado" label="Privado" />
        </flux:radio.group> --}}

        
        <flux:radio.group wire:model.live="derivado_canalizado" label="Herencia">
            <flux:radio value="derivado" label="Derivado" />
            <flux:radio value="canalizado" label="Canalizado" />
        </flux:radio.group>
    </div>

    <div>
        @if ($modalidad === 'linea')
        <div class="animate__animated animate__fadeIn">
            <flux:input wire:model="numero_ticket" :label="__('#Ticket')" type="text" required 
                placeholder="Número de Ticket" />
        </div>
        @endif
    </div>
    <div class="animate__animated animate__fadeIn">
        <flux:input wire:model="como_se_entero" :label="__('¿Como se entero?')" type="text" required 
            placeholder="Escriba como se entero de este servicio" />
    </div>
</div>
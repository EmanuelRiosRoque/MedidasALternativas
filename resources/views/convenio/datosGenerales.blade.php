<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    <div class="flex flex-wrap gap-x-6">
        <flux:radio.group wire:model.live="modalidad" label="Modalidad">
            <flux:radio value="presencial" label="Presencial" />
            <flux:radio value="linea" label="En línea" />
        </flux:radio.group>

        <flux:radio.group wire:model.live="materia" label="Materia">
            <flux:radio value="mercantil" label="Mercantil" />
            <flux:radio value="familiar" label="Familiar" />
        </flux:radio.group>

        {{-- <flux:radio.group wire:model.live="tipo_convenio" label="Tipo convenio">
            <flux:radio value="publico" label="Publico" />
            <flux:radio value="privado" label="Privado" />
        </flux:radio.group> --}}
    </div>

    <div>
        @if ($modalidad === 'linea')
        <div class="animate__animated animate__fadeIn">
            <flux:input wire:model="numero_ticket" :label="__('#Ticket')" type="text" required autofocus
                placeholder="Número de Ticket" />
        </div>
        @endif
    </div>
</div>
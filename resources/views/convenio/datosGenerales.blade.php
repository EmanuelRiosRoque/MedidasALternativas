<div x-data x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-6">

    {{-- Radios Livewire (estado en $wire) --}}
    <div class="flex flex-wrap gap-6">
        <flux:radio.group wire:model="modalidad" label="Modalidad">
            <flux:radio value="1" label="Presencial" />
            <flux:radio value="2" label="En línea" />
        </flux:radio.group>

        <flux:radio.group wire:model="materia" label="Materia">
            <flux:radio value="civil" label="Civil" />
            <flux:radio value="mercantil" label="Mercantil" />
            <flux:radio value="familiar" label="Familiar" />
        </flux:radio.group>

        <flux:radio.group wire:model="derivado_canalizado" label="¿Canalizado?">
            <flux:radio value="1" label="Sí" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>
    </div>

    {{-- Ticket SOLO si modalidad === "2" --}}
    <div
        x-show="$wire.modalidad == 2"
        x-transition.opacity.duration.150ms
    >
        <flux:input
            wire:model="numero_ticket"
            :label="__('#Ticket')"
            type="text"
            placeholder="Número de Ticket"
            {{-- opcional: requerido solo cuando se muestra --}}
            x-bind:required="$wire.modalidad == 2"
        />
    </div>

    {{-- Institución + Oficio SOLO si derivado_canalizado == 1 --}}
    <div
        class="space-y-5"
        x-show="Number($wire.derivado_canalizado) === 1"
        x-transition.opacity.duration.150ms
    >
        <flux:select
            wire:model.live="institucion"
            label="Institución"
            placeholder="Elige una institución"
        >
            <flux:select.option value="Fiscalía">Fiscalía</flux:select.option>
            <flux:select.option value="Juzgado">Juzgado</flux:select.option>
            <flux:select.option value="CDH">Comisión de Derechos Humanos de la CDMX</flux:select.option>
            <flux:select.option value="LUNAS">Secretaría de Mujeres (LUNAS)</flux:select.option>
            <flux:select.option value="Registro Civil">Juzgado de Registro Civil</flux:select.option>
            <flux:select.option value="Otro">Otro</flux:select.option>
        </flux:select>

        <div>
            <flux:heading class="flex items-center gap-2 mb-1">
                Oficio
                <flux:badge color="emerald" size="sm">Obligatorio</flux:badge>
            </flux:heading>

            <livewire:dropzone
                wire:model="oficio"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false"
                wire:key="oficio"
            />
        </div>
    </div>

    {{-- Campo "Otro" SOLO si institucion === "Otro" --}}
    <div
        x-show="$wire.institucion === 'Otro'"
        x-transition.opacity.duration.150ms
    >
        <flux:input
            wire:model="cual_otro"
            :label="__('Otro')"
            type="text"
            placeholder="Mencione cuál otro"
            x-bind:required="$wire.institucion === 'Otro'"
        />
    </div>
</div>

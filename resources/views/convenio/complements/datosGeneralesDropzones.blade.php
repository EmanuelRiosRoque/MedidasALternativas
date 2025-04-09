<div class="mt-5 mb-2 animate__animated animate__fadeIn tetx">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Identificacion
        <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
    </label>

    <livewire:dropzone
        wire:model="identificacion"
        :rules="['mimes:pdf','max:10420']"
        :multiple="false" />
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2">
    <flux:radio.group wire:model.live="representante" label="¿Es usted el representante?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="0" label="No" />
    </flux:radio.group>

    <div @if($representante != 1) style="display: none;" @endif class="col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
        
        <div class="col-span-2 sm:col-span-1 animate__animated animate__fadeIn">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Acta Notarial
            </label>
            <livewire:dropzone
                wire:model="acta_notarial"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false"
                wire:key="dropzone-acta-notarial"
            />
        </div>

        <div class="col-span-2 sm:col-span-1 animate__animated animate__fadeIn">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Acta de Nacimiento
            </label>
            <livewire:dropzone
                wire:model="acta_de_nacimiento"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false"
                wire:key="dropzone-acta-nacimiento"
            />
        </div>

        <div class="col-span-2 animate__animated animate__fadeIn">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Resolución Judicial
            </label>
            <livewire:dropzone
                wire:model="resolucion_judicial"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false"
                wire:key="dropzone-resolucion"
            />
        </div>
    </div>
</div>

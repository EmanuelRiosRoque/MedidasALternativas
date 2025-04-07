
<div class="mt-5 mb-2 animate__animated animate__fadeIn tetx">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Identificacion
        <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
    </label>
    <x-filepond::upload wire:model="identificacion" accept="application/pdf"/>    
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2"> 
<flux:radio.group wire:model.live="representante" label="Es usted el representante?">
    <flux:radio value="1" label="Si" />
    <flux:radio value="0" label="No" />
</flux:radio.group>

    @if ($representante == 1)
    <div class="mt-5 mb-2 animate__animated animate__fadeIn col-span-3">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Acta notarial
        </label>
        <x-filepond::upload wire:model="acta_notarial" accept="application/pdf"/>
    </div>
    <div class="mt-2 mb-2 animate__animated animate__fadeIn col-span-2">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Acta de Nacimiento
        </label>
        <x-filepond::upload wire:model="acta_de_nacimiento" accept="application/pdf"/>
    </div>
    <div class="mt-2 mb-2 animate__animated animate__fadeIn col-span-2">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Resolucion judicial
        </label>
        <x-filepond::upload wire:model="resolucion_judicial" accept="application/pdf"/>
    </div>
    @endif
</div>

<div class="mt-5 mb-2 animate__animated animate__fadeIn">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Identificacion
    </label>
    <x-filepond::upload wire:model="identificacion" />
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
        <x-filepond::upload wire:model="acta_notarial" />
    </div>
    @endif
</div>

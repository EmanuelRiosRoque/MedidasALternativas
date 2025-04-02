<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="flex flex-wrap gap-x-6">
        @if ($materia === 'mercantil')
        <div class="animate__animated animate__fadeIn">
            <flux:radio.group wire:model.live="persona" label="Persona" >
                <flux:radio value="fisica" label="Física" />
                <flux:radio value="moral" label="Moral" />
            </flux:radio.group>
        </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn"> 
    @if (!empty($persona) && $persona === 'fisica')
        @include('convenio.formularioPersonaFisica')
    @elseif (!empty($persona) && $persona === 'moral')
        @include('convenio.formularioPersonaMoral')
    @endif
</div>


<div class="mt-5 mb-2 animate__animated animate__fadeIn">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Identificacion
    </label>
    <x-filepond::upload wire:model="identificacion" />
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2"> 
<flux:radio.group wire:model.live="representante" label="Es ustede el representante?">
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
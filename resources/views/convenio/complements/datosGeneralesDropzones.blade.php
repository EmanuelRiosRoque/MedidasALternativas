<div class="mt-5 mb-2 animate__animated animate__fadeIn tetx">
    <flux:heading class="flex items-center gap-1 mb-1">
        Identificacion
        <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
        <flux:tooltip toggleable>
            <flux:button icon="information-circle" size="xs" variant="ghost" />
            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                <p>Idnetificaciones:</p>
                <ul>
                    <li>INE</li>
                    <li>Pasaporte</li>
                    <li>Cédula profecional</li>
                    <li>Licencia de conducir</li>
                    <li>Cartilla del servicio militar</li>
                    <li>Inapam</li>
                    <li>Documento migratorio</li>
                </ul>
            </flux:tooltip.content>
        </flux:tooltip>
    </flux:heading>

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
    @if ($representante == 1)    
    {{-- <flux:radio.group wire:model.live="doc_representante" label="¿Seleccione documento del representante?">
        <flux:radio value="1" label="Acta notarial" />
        <flux:radio value="2" label="Acata de nacimiento" />
        <flux:radio value="0" label="Resolucion judicial" />
    </flux:radio.group> --}}

    <flux:checkbox.group wire:model.live="doc_representante" label="Documento(s)">
        <flux:checkbox label="Acta notarial" value="1" />
        <flux:checkbox label="Acta de nacimiento" value="2"  />
        <flux:checkbox label="Resolucion judicial" value="3" />
    </flux:checkbox.group>
    @endif


    <div @if($representante != 1) style="display: none;" @endif class="col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
    
        <div class="col-span-2 sm:col-span-1 animate__animated animate__fadeIn"
            @if(!is_array($doc_representante) || !in_array(1, $doc_representante)) style="display: none;" @endif>
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
    
        <div class="col-span-2 sm:col-span-1 animate__animated animate__fadeIn"
            @if(!is_array($doc_representante) || !in_array(2, $doc_representante)) style="display: none;" @endif>
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
    
        <div class="col-span-2 animate__animated animate__fadeIn"
            @if(!is_array($doc_representante) || !in_array(3, $doc_representante)) style="display: none;" @endif>
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

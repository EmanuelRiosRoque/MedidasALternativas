@props(['prefix'])

<div class="grid grid-cols-2 gap-6 mb-10 mt-5">
    {{-- Identificación --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between ">
            <div class="flex items-center gap-2">
                <span class="font-medium text-zinc-100 text-sm">Identificación</span>
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
                @endif
            </div>
            <flux:tooltip toggleable>
                <flux:button icon="information-circle" size="xs" variant="ghost" />
                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                    <p>Identificaciones (Con fotografía):</p>
                    <ul class="list-disc list-inside text-xs text-zinc-600 dark:text-zinc-300">
                        <li>INE</li>
                        <li>Pasaporte</li>
                        <li>Cédula profesional</li>
                        <li>Licencia de conducir</li>
                        <li>Cartilla del servicio militar</li>
                        <li>INAPAM</li>
                        <li>Documento migratorio</li>
                    </ul>
                </flux:tooltip.content>
            </flux:tooltip>
        </div>

        <livewire:dropzone
            wire:model="identificacion"
            :rules="['mimes:pdf','max:10420']"
            :multiple="false"
            wire:key="identificacion"
        />
    </div>

    {{-- Formato de privacidad --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-zinc-100">Formato de Privacidad</span>
                <flux:badge color="red" inset="top bottom" size="sm">Firmado</flux:badge>
            </div>
            <flux:button
                variant="outline"
                size="xs"
                icon="link"
                @click.prevent="window.open('{{ asset('pdfs/formato-privacidad.pdf') }}', '_blank')"
            >
                Formato
            </flux:button>
        </div>

        <livewire:dropzone
            wire:model="formatoPrivacidad"
            :rules="['mimes:pdf','max:10420']"
            :multiple="false"
            wire:key="formatoPrivacidad"
        />
    </div>
</div>





<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2">
    <flux:radio.group wire:model.live="representante" label="¿Es usted el representante legal o albacea?">
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
        <flux:checkbox label="Acta de registro civil (Nacimiento o Matrimonio)" value="2"  />
        <flux:checkbox label="Resolucion judicial" value="3" />
    </flux:checkbox.group>

        @if ($materia == 'mercantil')
            <div class="animate__animated animate__fadeIn col-span-2 space-y-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    Nombre representante
                        <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
                </label>
                <flux:input wire:model="nombre_representante"  type="text" required 
                    placeholder="Nombre" />
            </div>
        @endif
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
                Acta de registro civil
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

@props(['prefix'])

<div class="grid grid-cols-2 gap-6 mb-10 mt-5">
    {{-- Identificación --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between ">
            <div class="flex items-center gap-2">
                <span class="font-medium text-zinc-700 dark:text-zinc-200 text-sm">Identificación</span>
                @if($prefix === 'solicitante')
                    <flux:badge color="emerald" inset="top bottom" size="sm">Obligatorio</flux:badge>
                @endif
            </div>
            <flux:tooltip toggleable>
            <flux:button icon="information-circle" size="sm" variant="ghost" />
            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                <p>Identificaciones (Con fotografía):</p>
                <p>INE</p>
                <p>Pasaporte</p>
                <p>Cédula profesional</p>
                <p>Licencia de conducir</p>
                <p>Cartilla del servicio militar</p>
                <p>INAPAM</p>
                <p>Documento migratorio</p>
            </flux:tooltip.content>
        </flux:tooltip>
        </div>

       

        <livewire:dropzone
            wire:model="identificacion"
            :rules="['mimes:pdf','max:10420']"
            :multiple="false"
            wire:key="identificacion-{{ $prefix }}"
        />
    </div>

    {{-- Formato de privacidad --}}
    <div class="space-y-1">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Formato de Privacidad</span>
                <flux:badge color="red" inset="top bottom" size="sm">Firmado</flux:badge>
            </div>
            <flux:button
                variant="outline"
                size="xs"
                icon="link"
                @click.prevent="window.open('{{ asset('pdfs/aviso_' . ($materia === 'familiar' ? 'familiar' : 'civil') . '.pdf') }}', '_blank')"
            >
                Formato
            </flux:button>
        </div>

        <livewire:dropzone
            wire:model="formato_privacidad"
            :rules="['mimes:pdf','max:10420']"
            :multiple="false"
            wire:key="formatoPrivacidad-{{ $prefix }}"
        />
    </div>
</div>

@if ($errors->has('identificacion') || $errors->has('formato_privacidad'))
    <p class="text-red-500 text-sm mt-1">Hace falta agregar al menos una indetificación o un aviso de privacidad.</p>
@endif

{{-- Radio Representante --}}
<div class="mb-2">
    <flux:radio.group wire:model="representante"
        label="¿Es usted el representante legal, albacea, endosatario en propiedad o endosatario en procuración?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="0" label="No" />
    </flux:radio.group>
</div>
{{-- Bloque Alpine/Livewire (no desmonta componentes) --}}
<div
    wire:key="rep-block-{{ $prefix }}"
    x-data="{
        // sincronía inmediata Alpine <-> Livewire
        representante: @entangle('representante').live,
        materia: @entangle('materia').live,
        doc: @entangle('doc_representante').live,

        isRep(){ return Number(this.representante) === 1 },
        esCivilMercantil(){ return ['mercantil','civil'].includes(String(this.materia||'')) },
        hasDoc(v){
            const d = this.doc || [];
            // comparamos como strings para cubrir '1' vs 1
            return d.some(x => String(x) === String(v));
        },
    }"
    x-cloak
    class="space-y-4"
>
    <!-- Opciones de documentos (visible solo si es representante) -->
    <div class="mb-2" x-show="isRep()" x-transition.opacity.duration.150ms>
        <flux:checkbox.group wire:model.live="doc_representante" label="Documento(s)" wire:key="docgrp-{{ $prefix }}">
            <flux:checkbox label="Intrumento notarial" value="1" :checked="in_array(1, $doc_representante)" />
            <flux:checkbox label="Acta de registro civil (Nacimiento o Matrimonio)" value="2" :checked="in_array(2, $doc_representante)" />
            <flux:checkbox label="Resolución judicial" value="3" :checked="in_array(3, $doc_representante)" />
            <flux:checkbox label="Título de crédito y póliza" value="4" :checked="in_array(4, $doc_representante)" />
        </flux:checkbox.group>
    </div>

    <!-- Datos del representante (visible solo si es rep y materia civil/mercantil) -->
    <div class="grid grid-cols-3 gap-2"
         x-show="isRep() && esCivilMercantil()"
         x-transition.opacity.duration.150ms>
        <div class="animate__animated animate__fadeIn col-span-1 space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Nombre
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="nombre_representante"
                type="text"
                required
                placeholder="Nombre" />
        </div>

        <div class="animate__animated animate__fadeIn col-span-1 space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Apellido paterno
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="apellido_p_representante"
                type="text"
                required
                placeholder="Apellido paterno" />
        </div>

        <div class="animate__animated animate__fadeIn col-span-1 space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Apellido materno
                <flux:badge color="emerald" size="sm" class="ml-2">Obligatorio</flux:badge>
            </label>
            <flux:input
                oninput="this.value = this.value.toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')"
                wire:model="apellido_m_representante"
                type="text"
                required
                placeholder="Apellido materno" />
        </div>
    </div>

    <!-- Dropzones SIEMPRE montados (solo ocultos/mostrados con x-show) -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2" x-show="isRep()" x-transition.opacity.duration.150ms>
        <div class="col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">

            <!-- Acta notarial -->
            <div class="col-span-2 sm:col-span-1"
                 x-show="hasDoc(1)"
                 x-transition.opacity.duration.120ms>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Instrumento Notarial
                </label>
                <livewire:dropzone
                    wire:model="acta_notarial"
                    :rules="['mimes:pdf','max:10420']"
                    :multiple="false"
                    wire:key="dropzone-acta-notarial-{{ $prefix }}"
                />
            </div>

            <!-- Acta de registro civil -->
            <div class="col-span-2 sm:col-span-1"
                 x-show="hasDoc(2)"
                 x-transition.opacity.duration.120ms>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Acta de registro civil
                </label>
                <livewire:dropzone
                    wire:model="acta_de_nacimiento"
                    :rules="['mimes:pdf','max:10420']"
                    :multiple="false"
                    wire:key="dropzone-acta-nacimiento-{{ $prefix }}"
                />
            </div>

            <!-- Resolución judicial -->
            <div class="col-span-2 sm:col-span-1"
                 x-show="hasDoc(3)"
                 x-transition.opacity.duration.120ms>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Resolución judicial
                </label>
                <livewire:dropzone
                    wire:model="resolucion_judicial"
                    :rules="['mimes:pdf','max:10420']"
                    :multiple="false"
                    wire:key="dropzone-resolucion-{{ $prefix }}"
                />
            </div>

            <!-- Título de crédito y póliza -->
            <div class="col-span-2 sm:col-span-1"
                 x-show="hasDoc(4)"
                 x-transition.opacity.duration.120ms>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Título de crédito y póliza
                </label>
                <livewire:dropzone
                    wire:model="titulo_credito"
                    :rules="['mimes:pdf','max:10420']"
                    :multiple="false"
                    wire:key="dropzone-titulo-{{ $prefix }}"
                />
            </div>

        </div>
    </div>
</div>

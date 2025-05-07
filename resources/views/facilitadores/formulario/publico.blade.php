<!-- Sección 1: Datos generales del facilitador -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2 animate__animated animate__fadeIn">
    <flux:input wire:model="duracion_encargo_facilitador" :label="__('Duración del encargo')" type="text" required placeholder="Duración del encargo" />
    <flux:input wire:model="numero_renovaciones_facilitador" :label="__('Número de renovaciones de certificación')" type="text" required placeholder="Número de renovaciones de certificación" />
    <flux:input wire:model="area_adscrito_facilitador" :label="__('Área de adscripción territorial')" type="number" required placeholder="Área de adscripción territorial" />
</div>
<flux:radio.group wire:model.live="apto" label="Dictamen">
    <flux:radio value="1" label="Apto" />
    <flux:radio value="2" label="No apto" />
</flux:radio.group>
<!-- Sección 2: Autoridades y documentos -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 animate__animated animate__fadeIn">



    <flux:radio.group wire:model.live="autoridad_certificacion_facilitador" label="Autoridad que otorgó la certificación">
        <flux:radio value="pjcdmx" label="PJCDMX" />
        <flux:radio value="pjfd" label="Poder Judicial Federal" />
        <flux:radio value="otro" label="Poder Judicial de otra entidad federativa" />
    </flux:radio.group>

    <div>
        @if ($autoridad_certificacion_facilitador === "otro")
        <flux:input wire:model="especificacion_autoridad" :label="__('Especificar')" type="text" required placeholder="Especificar" />
        @endif
    </div>

    <flux:radio.group wire:model.live="autorizacion_facilitador" label="¿Autorización para desempeñarse en otra entidad federativa?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        @if ($autorizacion_facilitador == 1)
        <div class="col-span-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo avale</label>
            <livewire:dropzone wire:model="avale_autorizado" :rules="['mimes:pdf','max:10420']" :multiple="false" />
        </div>
        @endif
    </div>

    <flux:radio.group wire:model.live="especializacion_facilitador" label="¿Cuenta con especialización en justicia restaurativa o terapéutica?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        @if ($especializacion_facilitador == 1)
        <div class="col-span-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo avale</label>
            <livewire:dropzone wire:model="avale_autorizado" :rules="['mimes:pdf','max:10420']" :multiple="false" />
        </div>
        @endif
    </div>

    <flux:radio.group wire:model.live="especializacion_arbitra_facilitador" label="¿Cuenta con especialización para desempeñarse como persona árbitra?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        @if ($especializacion_arbitra_facilitador == 1)
        <div class="col-span-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo avale</label>
            <livewire:dropzone wire:model="avale_autorizado_arbitra" :rules="['mimes:pdf','max:10420']" :multiple="false" />
        </div>
        @endif
    </div>
</div>

<!-- Sección 3: Convenios y sanciones -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 animate__animated animate__fadeIn">
    <flux:input wire:model="convenios_suscritos_facilitador" :label="__('Número de convenios suscritos')" type="number" required placeholder="Número de convenios suscritos" />
    <flux:input wire:model="convenios_ejecutados_facilitador" :label="__('Número de convenios ejecutados vía de apremio')" type="number" required placeholder="Número de convenios ejecutados vía de apremio" />

    <div class="flex flex-col gap-2">
        <flux:radio.group wire:model.live="quejas_recibidas_facilitador" label="¿Recibió quejas?">
            <flux:radio value="1" label="Sí" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>

        @if ($quejas_recibidas_facilitador == 1)
        <flux:radio.group wire:model.live="tiene_resolucion" label="¿Tiene resolución?">
            <flux:radio value="1" label="Sí" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>
        @endif
    </div>

    @if ($tiene_resolucion == 1)
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Resolución:</label>
        <livewire:dropzone wire:model="avale_resolucion" :rules="['mimes:pdf','max:10420']" :multiple="false" />
    </div>
    @endif

    <flux:input wire:model="infracciones_facilitador" :label="__('Infracciones cometidas')" type="text" required placeholder="Infracciones cometidas" />
    <flux:input wire:model="sanciones_facilitador" :label="__('Sanciones recibidas')" type="number" required placeholder="Sanciones recibidas" />
</div>

<!-- Sección 4: Documentos clave -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">CREE:</label>
        <livewire:dropzone wire:model="CREE" :rules="['mimes:pdf','max:10420']" :multiple="false" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Acuerdo plenario:</label>
        <livewire:dropzone wire:model="acuerdo_plenario" :rules="['mimes:pdf','max:10420']" :multiple="false" />
    </div>

    <div class="space-y-1">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Vigencia:</label>
        <livewire:dropzone wire:model="vigencia" :rules="['mimes:pdf','max:10420']" :multiple="false" />
    </div>
</div>

<!-- Sección 5: Supervisión y amparo -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
    <flux:radio.group wire:model.live="visitas_supervision" label="¿Recibió visitas de supervisión?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    @if ($visitas_supervision == 1)
    <div class="col-span-2 space-y-2">
        <flux:input wire:model="fecha_supervision" :label="__('Fecha de la supervisión')" type="date" required placeholder="Fecha de la supervisión" />
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Añadir video y/o acta de visita:</label>
            <livewire:dropzone wire:model="video_supervision" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
        </div>
    </div>
    @endif

    <flux:input wire:model="juicio_amparo" :label="__('Juicios de amparo')" type="text" required placeholder="Juicios de amparo" />
</div>

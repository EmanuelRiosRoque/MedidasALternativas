<div>
<!-- Sección 1: Datos generales del facilitador -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2" x-data="">
    <flux:input wire:model="duracion_encargo" :label="__('Duración del encargo')" type="text" required
        placeholder="Duración del encargo" />
    <flux:input wire:model="numero_renovaciones" :label="__('Número de renovaciones de certificación')" type="text"
        required placeholder="Número de renovaciones de certificación" />
    <flux:input wire:model="area_adscrito" :label="__('Área de adscripción territorial')" type="number" required
        placeholder="Área de adscripción territorial" />

</div>



<flux:radio.group wire:model.live="apto" label="Dictamen">
    <flux:radio value="1" label="Apto" />
    <flux:radio value="2" label="No apto" />
</flux:radio.group>

<!-- Sección 2: Autoridades y documentos -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4" x-data="{
        autorizacion: @entangle('autorizacion').live,
        especializacion: @entangle('especializacion').live,
        especializacion_arbitra: @entangle('especializacion_arbitra').live,
        autoridad_certificacion: @entangle('autoridad_certificacion').live,
        tiene_resolucion: @entangle('tiene_resolucion').live,
    }">
    <flux:radio.group wire:model.live="autoridad_certificacion"
        label="Nombre del Poder Judicial que otorgó la certificación o renovación">
        <flux:radio value="pjcdmx" label="PJCDMX" />
        <flux:radio value="pjfd" label="Poder Judicial Federal" />
        <flux:radio value="otro" label="Poder Judicial de otra entidad federativa" />
    </flux:radio.group>

    <div class=" space-y-1">
        <div x-show="autoridad_certificacion == 'otro'" x-cloak class="mt-4">
            <flux:input wire:model="especificacion_autoridad" :label="__('Especificar')" type="text" required
                placeholder="Especificar" />
        </div>
        <flux:input wire:model="clave_autoridad" :label="__('Clave')" type="text" required placeholder="Clave" />
    </div>


    <flux:radio.group wire:model="autorizacion" label="¿Autorización para desempeñarse en otra entidad federativa?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="autorizacion == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo
                    avale</label>
                <livewire:dropzone wire:model="avale_autorizado" :rules="['mimes:pdf','max:10420']" :multiple="false" />
            </div>
        </div>
    </div>

    <flux:radio.group wire:model="especializacion"
        label="¿Cuenta con especialización en justicia restaurativa o terapéutica?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="especializacion == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo
                    avale</label>
                <livewire:dropzone wire:model="avale_autorizado" :rules="['mimes:pdf','max:10420']" :multiple="false" />
            </div>
        </div>
    </div>

    <flux:radio.group wire:model="especializacion_arbitra"
        label="¿Cuenta con especialización para desempeñarse como persona árbitra?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="especializacion_arbitra == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">Documento que lo
                    avale</label>
                <livewire:dropzone wire:model="avale_autorizado_arbitra" :rules="['mimes:pdf','max:10420']"
                    :multiple="false" />
            </div>
        </div>
    </div>

</div>

<!-- Sección 3: Convenios y sanciones -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6" x-data="{
        tiene_resolucion: @entangle('tiene_resolucion').live,
    }">
    <flux:input wire:model="convenios_suscritos" :label="__('Número de convenios suscritos')" type="number" required
        placeholder="Número de convenios suscritos" />

    <flux:input wire:model="convenios_ejecutados" :label="__('Número de convenios ejecutados vía de apremio')"
        type="number" required placeholder="Número de convenios ejecutados vía de apremio" />

    <flux:input wire:model="Procedimientos_quejas" :label="__('Procedimientos de queja')" type="text" required
        placeholder="Infracciones cometidas" />

    <flux:radio.group wire:model="tiene_resolucion" label="¿Tiene resolución?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div x-show="tiene_resolucion == 1" x-cloak class="mt-1 col-span-2">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Resolución:</label>
        <livewire:dropzone wire:model="avale_resolucion" :rules="['mimes:pdf','max:10420']" :multiple="false" />
    </div>

    <div class="col-span-2">
        <flux:input wire:model="infracciones" :label="__('Infracciones cometidas')" type="text" required
            placeholder="Infracciones cometidas" />
    </div>
</div>

<div class="mt-1 grid grid-cols-2">
    <flux:radio.group wire:model="cancelacion" label="Descripción de Sanciones impuestas, en su caso">
        <flux:radio value="1" label="Amonestación" />
        <flux:radio value="2" label="Sanción económica" />
        <flux:radio value="3" label="Reparación del Daño" />
        <flux:radio value="4" label="Suspensión de la certificación" />
        <flux:radio value="5" label="Revocación de la certificación" />
        <flux:radio value="6" label="Inhabilitación" />
    </flux:radio.group>

    <flux:radio.group wire:model="cancelacion" label="Cancelación de Registro">
        <flux:radio value="1" label="Suspensión" />
        <flux:radio value="2" label="Revocación" />
        <flux:radio value="3" label="Inhabilitación" />
        <flux:radio value="4" label="Revocación" />
        <flux:radio value="5" label="Solicitud" />
        <flux:radio value="6" label="Fallecimiento" />
        <flux:radio value="7" label="Vencimiento" />
    </flux:radio.group>
</div>


<!-- Sección 4: Documentos clave -->
<div class="flex flex-col mt-6">
    <flux:link href="#">Hipervínculo de certificación inicial</flux:link>
    <flux:link href="#">Hipervínculo a Vigencia</flux:link>
    <flux:link href="#">Hipervínculo a Acuerdo Plenario (Renovación)</flux:link>
    <flux:link href="#">Determinación del Órgano Instructor o área que se determine</flux:link>
</div>

<!-- Sección 5: Supervisión y amparo -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6" x-data="{
        visitas_supervision: @entangle('visitas_supervision').live,
        dictamen_cja: @entangle('dictamen_cja').live,
    }">
    <flux:radio.group wire:model="elementos_materiales" label="Elementos materiales para el ejercicio de su función">
        <flux:radio value="1" label="Registro de sello, rúbrica o media firma y firma ante el R.P.P.C.CDMX" />
        <flux:radio value="2" label="Registro ante SAT" />
    </flux:radio.group>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo avale</label>
        <livewire:dropzone wire:model="video_supervision" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="visitas_supervision" label="¿Recibió visitas de supervisión?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div x-show="visitas_supervision == 1" x-cloak class="mt-1 col-span-2">
        <div class="col-span-2 space-y-2">
            <flux:input wire:model="fecha_supervision" :label="__('Fecha de la supervisión')" type="date" required
                placeholder="Fecha de la supervisión" />
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Añadir video y/o
                    acta de
                    visita:</label>
                <livewire:dropzone wire:model="video_supervision" :rules="['mimes:mp4,pdf','max:10420']"
                    :multiple="true" />
            </div>
        </div>
    </div>

    <flux:input wire:model="juicio_amparo" :label="__('Juicios de amparo')" type="text" required
        placeholder="Juicios de amparo" />

    <flux:input wire:model="fecha_publicacion"
        :label="__('Fecha de publicación de la certificación o renovación en Boletín Judicial o Gaceta Oficial de la CDMX')"
        type="date" required />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo
            avale</label>
        <livewire:dropzone wire:model="publicacion_documento" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="dictamen_cja" label="Dictamen CJA">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>
    <div>
        <div x-show="dictamen_cja == 1" x-cloak class="mt-1 col-span-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Añadir Dictamen</label>
            <livewire:dropzone wire:model="publicacion_documento" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
        </div>
    </div>
</div>
</div>

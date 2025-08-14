<!-- Sección 1: Datos generales del facilitador -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2" x-data="">
    <flux:input wire:model="duracion_encargo" :label="__('Duración del encargo')" type="text" required
        placeholder="Duración del encargo" />
    
    <flux:input wire:model="area_adscrito" :label="__('Área de adscripción territorial')" type="number" required
        placeholder="Área de adscripción territorial" />
</div>


<div class="space-y-1">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
        Número de renovaciones de certificación
    </label>

    <div class="flex gap-2">
        <flux:input oninput="
            let nums = this.value.replace(/[^0-9]/g, ''); // solo números
            let v = '';

            if (nums.length > 0) v = nums.slice(0, 2);
            if (nums.length > 2) v += '/' + nums.slice(2, 4);
            if (nums.length > 4) v += '/' + nums.slice(4, 8);
            if (nums.length > 8) v += ' - ' + nums.slice(8, 10);
            if (nums.length > 10) v += '/' + nums.slice(10, 12);
            if (nums.length > 12) v += '/' + nums.slice(12, 16);

            this.value = v;
        " wire:model.defer="numero_renovaciones" type="text" placeholder="DD/MM/YYYY - DD/MM/YYYY" />
        <flux:button variant="primary" wire:click="agregarPeriodo">
            Agregar
        </flux:button>
    </div>

    @if (!empty($periodos))
    <ul class="mt-2 space-y-1">
        @foreach ($periodos as $i => $per)
        <li
            class="flex justify-between items-center text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800 px-3 py-1 rounded">
            <span class="truncate">{{ $per }}</span>
            <button wire:click="eliminarPeriodo({{ $i }})"
                class="ml-3 text-xs text-red-600 hover:underline hover:bg-red-100 px-1 rounded" title="Eliminar">
                ×
            </button>
        </li>
        @endforeach
    </ul>
    @endif
</div>





<!-- Sección 2: Autoridades y documentos -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4" x-data="{
        autorizacion: @entangle('autorizacion').live,
        especializacion: @entangle('especializacion').live,
        especializacion_arbitra: @entangle('especializacion_arbitra').live,
        autoridad_certificacion: @entangle('autoridad_certificacion').live,
        tiene_resolucion: @entangle('tiene_resolucion').live,
    }">

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Nombre del Poder Judicial que otorgó la certificación o renovación
        </label>
        <flux:select wire:model="autoridad_certificacion" placeholder="Elige una institución">
            <flux:select.option>PJCDMX</flux:select.option>
            <flux:select.option>Poder Judicial Federal</flux:select.option>
        </flux:select>
    </div>

    <div class=" space-y-1">
        <div x-show="autoridad_certificacion == 'otro'" x-cloak class="mt-4">
            <flux:input wire:model="especificacion_autoridad" :label="__('Especificar')" type="text" required
                placeholder="Especificar" />
        </div>
        <flux:input wire:model="clave_autoridad" :label="__('Clave de la Entidad Federativa que otorgó la Certificación o renovación ')" type="text" maxlength="4" required placeholder="Clave" />
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
                <livewire:dropzone wire:model="avale_especializacion" :rules="['mimes:pdf','max:10420']" :multiple="false" />
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

  

    <flux:radio.group wire:model="tiene_resolucion" label="¿Tiene resolución?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="tiene_resolucion == 1" x-cloak class="mt-1 col-span-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Resolución:</label>
            <livewire:dropzone wire:model="avale_resolucion" :rules="['mimes:pdf','max:10420']" :multiple="false" />
        </div>
    </div>
        
    <div class="col-span-2">
        <flux:input wire:model="infracciones" :label="__('Infracciones cometidas')" type="text" required
            placeholder="Infracciones cometidas" />
    </div>
</div>

<div class="mt-1 grid grid-cols-2">
    <flux:radio.group wire:model="descripcion_sancion" label="Descripción de Sanciones impuestas, en su caso">
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
        juicio_amparo: @entangle('juicio_amparo').live
    }">
    <flux:radio.group wire:model="elementos_materiales" label="Elementos materiales para el ejercicio de su función">
        <flux:radio value="1" label="Registro de sello, rúbrica o media firma y firma ante el R.P.P.C.CDMX" />
        <flux:radio value="2" label="Registro ante SAT" />
    </flux:radio.group>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo avale</label>
        <livewire:dropzone wire:model="avale_materiales" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="visitas_supervision" label="¿Recibió visitas de supervisión?">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="visitas_supervision == 1" x-cloak class="mt-1 col-span-2">
            <flux:input wire:model="fecha_supervision" :label="__('Fecha de la supervisión')" type="date" required
                placeholder="Fecha de la supervisión" />
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Añadir video y/o
                    acta de
                    visita:</label>
                <livewire:dropzone wire:model="video_supervision" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
            </div>
        </div>
    </div>

    <flux:input wire:model="fecha_publicacion"
        :label="__('Fecha de publicación de la certificación o renovación en Boletín Judicial o Gaceta Oficial de la CDMX')"
        type="date" required />
    
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo
            avale</label>
        <livewire:dropzone wire:model="publicacion_documento" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="juicio_amparo" label="¿Jucio Amparo?">
       <flux:radio value="1" label="Sí" />
       <flux:radio value="2" label="No" />
    </flux:radio.group>
    <div>
       <div x-show="juicio_amparo == 1" x-cloak class="mt-1 col-span-2">
           <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo avale</label>
           <livewire:dropzone wire:model="avale_jucio" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
       </div>
    </div>
   
    <flux:radio.group wire:model="dictamen_cja" label="Dictamen CJA">
       <flux:radio value="1" label="Sí" />
       <flux:radio value="2" label="No" />
    </flux:radio.group>
    <div>
       <div x-show="dictamen_cja == 1" x-cloak class="mt-1 col-span-2">
           <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Añadir Dictamen</label>
           <livewire:dropzone wire:model="avale_dictamen" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
       </div>
    </div>
</div>



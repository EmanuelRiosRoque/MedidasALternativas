<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-2 animate__animated animate__fadeIn"> 

    <flux:input
        wire:model="duracion_encargo_facilitador"
        :label="__('Duracion de encargo')"
        type="text"
        required
        placeholder="Duracion de encargo"
    />

    <flux:input
        wire:model="numero_ratificaciones_facilitador"
        :label="__('Numero de ratificaciones')"
        type="text"
        required
        placeholder="Numero de ratificaciones"
    />

    <flux:input
        wire:model="area_adscrito_facilitador"
        :label="__('Area adscripcion territorial')"
        type="number"
        required
        placeholder="Area adscripcion territorial"
    />
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn"> 

    <flux:radio.group wire:model.live="autoridad_certificacion_facilitador" label="Autoridad que otrogó la ceritifcación">
        <flux:radio value="pjcdmx" label="PJCDMX" />
        <flux:radio value="pjfd" label="Poder Judicial Federal" />
        <flux:radio value="otro" label="Poder Judicial de otra entidad Federativa" />
    </flux:radio.group>
    <div>
        @if ($autoridad_certificacion_facilitador === "otro")
            <flux:input
                wire:model="especificacion_autoridad"
                :label="__('Especificar')"
                type="text"
                required
                placeholder="Especificar"
            />
        @endif
    </div>

    <flux:radio.group wire:model.live="autorizacion_facilitador" label="Autorización para poder desempeñarse en otra Entidad Federativa">
        <flux:radio value="1" label="Si" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        @if ($autorizacion_facilitador == 1)
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Documento que avale
            </label>
            <livewire:dropzone
                wire:model="avale_autorizado"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false" />
        @endif
    </div>

    <flux:radio.group wire:model.live="especializacion_facilitador" label="Cuenta con especialización de justicia restauratica o teraprútica">
        <flux:radio value="1" label="Si" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        @if ($especializacion_facilitador == 1)
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Documento que avale
            </label>
            <livewire:dropzone
                wire:model="avale_autorizado"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false" />
        @endif
    </div>

    <flux:radio.group wire:model.live="especializacion_arbitra_facilitador" label="Cuenta con especialización para desempeñar como persona árbitra">
        <flux:radio value="1" label="Si" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>
    
    <div>
        @if ($especializacion_arbitra_facilitador == 1)
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Documento que avale
            </label>
            <livewire:dropzone
                wire:model="avale_autorizado_arbitra"
                :rules="['mimes:pdf','max:10420']"
                :multiple="false" />
        @endif
    </div>

</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2 animate__animated animate__fadeIn"> 

    <flux:input
        wire:model="convenios_suscritos_facilitador"
        :label="__('Numero de convenios suscritos')"
        type="number"
        required
        placeholder="Numero de convenios suscritos"
    />

    <flux:input
        wire:model="convenios_ejecutados_facilitador"
        :label="__('Numero de convenios ejecutados vía de apremio')"
        type="number"
        required
        placeholder="Numero de convenios ejecutados vía de apremio"
    />
    <div class="flex gap-5">
        <flux:radio.group wire:model.live="quejas_recibidas_facilitador" label="Quejas recibidas">
            <flux:radio value="1" label="Si" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>
        @if ($quejas_recibidas_facilitador == 1)   
        <flux:radio.group wire:model.live="tiene_resolucion" label="¿Tiene_resolución?">
            <flux:radio value="1" label="Si" />
            <flux:radio value="2" label="No" />
        </flux:radio.group>
        @endif
    </div>

    <div>
        @if ($tiene_resolucion == 1)
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Resolucion:
        </label>
        <livewire:dropzone
            wire:model="avale_resolucion"
            :rules="['mimes:pdf','max:10420']"
            :multiple="false" />
        @endif
    </div>

    <flux:input
        wire:model="infracciones_facilitador"
        :label="__('Infracciones recibidas')"
        type="text"
        required
        placeholder="Infracciones recibidas"
    />

    <flux:input
        wire:model="sanciones_facilitador"
        :label="__('Sanciones recibidas')"
        type="number"
        required
        placeholder="Sanciones recibidas"
    />
    <div class=" flex justify-center mt-5 flex-col">
        <flux:text>Impervinculo a <flux:link href="#">Vigencia</flux:link>.</flux:text>
        <flux:text>Impervinculo a <flux:link href="#">Acuerdo plenario</flux:link>.</flux:text>
        <flux:text>Determinación del <flux:link href="#">CREE</flux:link>.</flux:text>   
    </div>

    <flux:radio.group wire:model.live="visustas_supervision" label="Visitas Supervision">
        <flux:radio value="1" label="Si" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>
    
    <div class=" col-span-2">
        @if ($visustas_supervision == 1)
            <flux:input
                wire:model="fecha_supervision"
                :label="__('Fecha de la supervision')"
                type="date"
                required
                placeholder="Fecha de la supervision"
            />
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                Añadir video:
            </label>
            <livewire:dropzone
            wire:model="video_supervision"
            :rules="['mimes:mp4','max:10420']"
            :multiple="false" />
        @endif
    </div>
</div>
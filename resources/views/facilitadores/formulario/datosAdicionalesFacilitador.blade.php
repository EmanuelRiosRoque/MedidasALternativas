<!-- Sección 1: Datos generales del facilitador -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2" x-data="">
    <flux:input wire:model="duracion_encargo" :label="__('Duración del encargo *')" type="text" required
        placeholder="Duración del encargo" value="5 años en el encargo" />

    <flux:input wire:model="area_adscrito" :label="__('Área de adscripción territorial *')" type="number" required
        placeholder="Área de adscripción territorial" min="1" />

</div>


<div class="mt-2">
    <flux:input wire:model="numero_renovaciones" :label="__('Número de renovaciones de certificación *')" type="text"
        maxlength="2" pattern="\d*" inputmode="numeric" required placeholder="Hasta 15 renovaciones (máx. 2 dígitos)" />
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
            Nombre del Poder Judicial que otorgó la certificación o renovación *
        </label>

        <flux:select x-data wire:model.live="autoridad_certificacion"
            @change="$wire.set('clave_autoridad', $event.target.selectedOptions[0]?.dataset.cve ?? '')"
            placeholder="Elige una institución">
            @foreach ($poderesJudiciales as $pj)
            <flux:select.option value="{{ $pj['poder_judicial'] }}" data-cve="{{ $pj['cve_ent'] }}">
                {{ $pj['poder_judicial'] }}
            </flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Clave de la Entidad Federativa que otorgó la certificación o renovación *
        </label>
        <flux:input wire:model="clave_autoridad" placeholder="Se autocompleta al elegir el Poder Judicial" readonly />
    </div>


    <flux:radio.group wire:model="autorizacion" label="¿Autorización para desempeñarse en otra entidad federativa? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="autorizacion == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Documento que lo avale *
                </label>
                <livewire:dropzone wire:model="avale_autorizado" :rules="['mimes:pdf','max:10420']" :multiple="false" />
            </div>
        </div>
    </div>

    <flux:radio.group wire:model="especializacion"
        label="¿Cuenta con especialización en justicia restaurativa o terapéutica? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="especializacion == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Documento que lo avale *
                </label>
                <livewire:dropzone wire:model="avale_especializacion" :rules="['mimes:pdf','max:10420']"
                    :multiple="false" />
            </div>
        </div>
    </div>

    <flux:radio.group wire:model="especializacion_arbitra"
        label="¿Cuenta con especialización para desempeñarse como persona árbitra? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="especializacion_arbitra == 1" x-cloak class="mt-4">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                    Documento que lo avale *
                </label>
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

    <flux:input wire:model="convenios_suscritos" :label="__('Número de convenios suscritos *')" type="number"
        placeholder="Número de convenios suscritos" />

    <flux:input wire:model="convenios_ejecutados" :label="__('Número de convenios ejecutados vía de apremio *')"
        type="number" placeholder="Número de convenios ejecutados vía de apremio" />

    <flux:radio.group wire:model="tiene_resolucion" label="¿Tiene resolución? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="tiene_resolucion == 1" x-cloak class="mt-1 col-span-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Resolución: *</label>
            <livewire:dropzone wire:model="avale_resolucion" :rules="['mimes:pdf','max:10420']" :multiple="false" />
        </div>
    </div>

    {{-- <div class="col-span-2">
        <flux:input wire:model="infracciones" :label="__('Infracciones cometidas *')" type="text"
            placeholder="Infracciones cometidas" />
    </div> --}}


    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-2">
            Infracciones cometidas *
        </label>
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Suspensión</p>
        <div class="space-y-1">
            <flux:checkbox wire:model="infracciones" value="s1"
                label="Ostentarse como persona Facilitadora en algún MASC, del que no forme parte" />
            <flux:checkbox wire:model="infracciones" value="s2"
                label="Ejercer coacción o violencia contra alguna de las partes" />
            <flux:checkbox wire:model="infracciones" value="s3"
                label="Abstenerse de informar la improcedencia del MASC" />
            <flux:checkbox wire:model="infracciones" value="s4"
                label="Realizar actuaciones de pública fuera de los casos previstos en la LGMASC" />
            <flux:checkbox wire:model="infracciones" value="s5"
                label="Otra conducta determinada por la normatividad aplicable" />
        </div>
    </div>

    <div class=" mt-2">
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-4 mb-1">Revocación</p>
        <div class="space-y-1">
            <flux:checkbox wire:model="infracciones" value="r1" label="Falta grave en términos de la LGMASC" />
            <flux:checkbox wire:model="infracciones" value="r2"
                label="Sentencia condenatoria por delito doloso con pena privativa de libertad" />
            <flux:checkbox wire:model="infracciones" value="r3"
                label="Reincidir participando en MASC con causa de impedimento sin excusarse" />
            <flux:checkbox wire:model="infracciones" value="r4"
                label="Delegar o permitir a un tercero el uso de su certificación" />
            <flux:checkbox wire:model="infracciones" value="r5"
                label="Las demás señaladas en la LGMASC y normatividad aplicable" />
        </div>
    </div>


</div>

<!-- Sección 4: Descripcion y cancelacion -->
<div class="mt-4 grid grid-cols-2">
    <flux:radio.group wire:model="descripcion_sancion" label="Descripción de Sanciones impuestas, en su caso *">
        <flux:radio value="1" label="Amonestación" />
        <flux:radio value="2" label="Sanción económica" />
        <flux:radio value="3" label="Reparación del Daño" />
        <flux:radio value="4" label="Suspensión de la certificación" />
        <flux:radio value="5" label="Revocación de la certificación" />
        <flux:radio value="6" label="Inhabilitación" />
    </flux:radio.group>

    <flux:radio.group wire:model="cancelacion" label="Cancelación de Registro *">
        <flux:radio value="1" label="Suspensión" />
        <flux:radio value="3" label="Inhabilitación" />
        <flux:radio value="4" label="Revocación" />
        <flux:radio value="5" label="Solicitud" />
        <flux:radio value="6" label="Fallecimiento" />
        <flux:radio value="7" label="Vencimiento" />
    </flux:radio.group>
</div>

<!-- Sección 5: Documentos clave -->
<div class="flex flex-col mt-6 text-sm">
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

    <flux:radio.group wire:model="elementos_materiales" label="Elementos materiales para el ejercicio de su función *">
        <flux:radio value="1" label="Registro de sello, rúbrica o media firma y firma ante el R.P.P.C.CDMX" />
        <flux:radio value="2" label="Registro ante SAT" />
    </flux:radio.group>

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">Documento que lo avale
            *</label>
        <livewire:dropzone wire:model="avale_materiales" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="visitas_supervision" label="¿Recibió visitas de supervisión? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="visitas_supervision == 1" x-cloak class="mt-1 col-span-2">
            <flux:input wire:model="fecha_supervision" :label="__('Fecha de la supervisión *')" type="date"
                placeholder="Fecha de la supervisión" />
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">
                    Añadir video y/o acta de visita: *
                </label>
                <livewire:dropzone wire:model="video_supervision" :rules="['mimes:mp4,pdf','max:10420']"
                    :multiple="true" />
            </div>
        </div>
    </div>

    <flux:input wire:model="fecha_publicacion"
        :label="__('Fecha de publicación de la certificación o renovación en Boletín Judicial o Gaceta Oficial de la CDMX *')"
        type="date" />

    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">
            Documento que lo avale *
        </label>
        <livewire:dropzone wire:model="publicacion_documento" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
    </div>

    <flux:radio.group wire:model="juicio_amparo" label="¿Jucio Amparo? *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>
    <div>
        <div x-show="juicio_amparo == 1" x-cloak class="mt-1 col-span-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">
                Documento que lo avale *
            </label>
            <livewire:dropzone wire:model="avale_jucio" :rules="['mimes:mp4,pdf','max:10420']" :multiple="false" />
        </div>
    </div>

    <flux:radio.group wire:model="dictamen_cja" label="Dictamen CJA *">
        <flux:radio value="1" label="Sí" />
        <flux:radio value="2" label="No" />
    </flux:radio.group>

    <div>
        <div x-show="dictamen_cja == 1" x-cloak class="mt-1 col-span-2">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1 mt-1">
                Añadir Dictamen *
            </label>
            <livewire:dropzone wire:model="avale_dictamen" :rules="['mimes:mp4,pdf','max:10420']" :multiple="true" />
        </div>
    </div>

    <div class=" col-span-2">
        <p class=" text-justify">
            Artículo 46 de la Ley General de Mecanismos Alternativos de Solución de
            Controversias. Las personas Facilitadoras deberán excusarse o podrán
            ser recusadas para conocer de los asuntos, de conformidad con lo
            dispuesto en el artículo 104 del Código Nacional de Procedimientos
            Civiles y Familiares y demás disposiciones aplicables.
        </p>
    </div>

    <div class=" col-span-2">
        <flux:checkbox.group wire:model="excusas"
            label="Artículo 104 del Código Nacional de Procedimientos Civiles y Familiares:">
            <flux:checkbox value="1" label="I.Cuando tengan interés directo o indirecto en el procedimiento." />
            <flux:checkbox value="2"
                label="II.En los procedimientos que sean del mismo interés para su cónyuge, concubina, concubinario, conviviente o para sus parientes consanguíneos en línea recta sin limitación de grados, a los colaterales dentro del cuarto grado, y a los afines dentro del segundo." />
            <flux:checkbox value="3"
                label="III.Siempre que, entre su cónyuge, concubina, concubinario, conviviente, ascendientes o sus descendientes, y alguno de las partes interesadas, haya relación de intimidad nacida de algún acto civil o religioso, relación de amistad o económica, de subordinación o lealtad, sin importar su origen." />
            <flux:checkbox value="4"
                label="IV.Si fuere pariente por consanguinidad o afinidad de la persona representante autorizada, abogado o procurador de alguna de las partes, en los mismos grados de la fracción II." />
            <flux:checkbox value="5"
                label="V.Cuando la autoridad jurisdiccional, su cónyuge, concubina, concubinario, conviviente o alguno de sus ascendientes o descendientes sea parte heredera, legataria, donante, donataria, socia, acreedora, deudora, fiadora, fiada, arrendadora, arrendataria, principal, dependiente o comensal habitual de alguna de las partes, o administradora actual de sus bienes." />
            <flux:checkbox value="6"
                label="VI.Si ha hecho promesas o amenazas, o ha manifestado odio o afecto por alguna de las partes; o ha sido sujeto de amenazas o la animadversión de alguna de las partes ha influido en su fuero interno." />
            <flux:checkbox value="7"
                label="VII.Si asiste o ha asistido a convites que especialmente se le ofrecieren o costeare alguna de las partes o sus representantes, antes o después de comenzado el procedimiento, o si se tiene familiaridad con los mencionados, o cohabitan con ellas." />
            <flux:checkbox value="8"
                label="VIII.Cuando, después de iniciado el procedimiento, la autoridad jurisdiccional, su cónyuge, concubina, concubinario, conviviente, ascendientes o descendientes, parientes colaterales en segundo grado y por afinidad en primer grado, haya recibido dádivas o servicios de alguna de las partes." />
            <flux:checkbox value="9"
                label="IX.Si ha sido abogado o procurador, ha fungido como apoyo o ha recibido apoyo para el ejercicio de la capacidad jurídica, perito o testigo en el procedimiento de que se trate o de cualquiera de las partes, en éste o en cualquier otro procedimiento." />
            <flux:checkbox value="10"
                label="X.Si ha conocido del procedimiento como autoridad jurisdiccional, árbitro o asesor, resolviendo algún punto que afecte a la sustancia de la cuestión, en la misma instancia o en otra." />
            <flux:checkbox value="11"
                label="XI.Cuando la autoridad jurisdiccional, su cónyuge, concubina, concubinario, conviviente o parientes consanguíneos en línea recta sin limitación, colaterales dentro del segundo o afines en el primero, sigan contra alguna de las partes, o no haya pasado un año de haber seguido juicio civil o causa criminal como parte acusadora, querellante o denunciante, o parte civil en causa criminal contra cualquiera de ellas." />
            <flux:checkbox value="12"
                label="XII.Cuando alguna de las personas representantes autorizadas sigan o hayan seguido un juicio civil o causa criminal, y no haya pasado un año o más de haber causado ejecutoria, un procedimiento jurisdiccional contra la autoridad jurisdiccional, su cónyuge, concubina, concubinario, conviviente, ascendientes o descendientes, parientes colaterales en segundo grado y por afinidad en primer grado." />
            <flux:checkbox value="13"
                label="XIII.Cuando la persona servidora pública, su cónyuge, concubina, concubinario, conviviente, ascendientes o descendientes, parientes colaterales en segundo grado y por afinidad en primer grado, sea contraria a cualquiera de las partes en procedimiento administrativo que afecte a sus intereses." />
            <flux:checkbox value="14"
                label="XIV.Si la persona servidora pública, su cónyuge, concubina, concubinario, conviviente o alguno de sus expresados parientes sigue algún procedimiento civil o criminal en que sea autoridad jurisdiccional, agente del Ministerio Público, Procurador o Representante Social, árbitro o arbitrador, de alguno de los litigantes." />
            <flux:checkbox value="15"
                label="XV.Si es persona tutora, tutriz, curador o curadora de alguna de las partes interesadas, administra sus bienes, es gerente de alguna sociedad o asociación que tenga interés en la causa o no hayan pasado tres años de haberlo sido." />
            <flux:checkbox value=""
                label="XVI.Siempre que haya externado su opinión públicamente, adelantando el sentido de su fallo." />
        </flux:checkbox.group>
    </div>

</div>
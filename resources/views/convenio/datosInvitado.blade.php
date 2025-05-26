{{--* Radio para manejar tipo persona --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative">

    <div class=" flex justify-center col-span-2">
        <h1 class="text-2xl border-b-2 border-emerald-600">Invitado</h1>
    </div>

    <div class="flex flex-wrap gap-x-6">
        @if ($materia === 'mercantil' || $materia=== 'civil')
        <div class="animate__animated animate__fadeIn">
            <flux:radio.group wire:model.live="persona" label="Persona" >
                <flux:radio value="fisica" label="Física" />
                <flux:radio value="moral" label="Moral" />
            </flux:radio.group>
        </div>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            ¿Cómo se enteró?
        </label>
        <flux:select wire:model="como_se_entero" placeholder="¿Cómo se enteró?">
            @foreach ($mediosInvitado as $medio)
                <flux:select.option>{{ $medio }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>
    <div class="w-full flex justify-center col-span-2">
        <div wire:loading.delay.shortest wire:target="persona" >
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-emerald-600"></div>
        </div>
    </div>
</div>


{{--* Formularios por TIPO PERSONA --}}
<div wire:loading.remove wire:target='persona'>
@if (!empty($persona) && $persona === 'fisica')
    @include('convenio.formulario.personaFisica', ['prefix' => 'invitado', 'key' => 'fisica'])
@elseif (!empty($persona) && $persona === 'moral')
    @include('convenio.formulario.personaMoral', ['prefix' => 'invitado', 'key' => 'moral'])
@endif
</div>
@if ($materia === 'familiar')
    @include('convenio.formulario.materiaFamiliar', ['prefix' => 'invitado', 'key' => 'familiar'])
@endif

{{--* Dropzone y representante --}}
@include('convenio.complements.datosGeneralesDropzones', ['prefix' => 'invitado'])

{{--** Boton para enviar datos --}}
<div class="flex justify-end mt-2">
     <flux:button wire:click='{{ $modoEdicion ? "editarPersona" : "agregarPersona(\"invitado\")" }}' variant="primary" type="button">
        {{ $modoEdicion ? 'Actualizar' : 'Agregar' }}
    </flux:button>
</div>

{{--* Cards donde se visualizan los participantes agregados --}}
@if (!empty($invitadoArray))
    @if(collect($invitadoArray)->where('persona', 'fisica')->count())
        <x-convenio.tabla-participantes
        heading="Invitados Persona Física"
        :solicitantes="collect($invitadoArray)->where('persona', 'fisica')"
        tipo="invitado"
        />
    @endif

    @if(collect($invitadoArray)->where('persona', 'moral')->count())
        <x-convenio.tabla-participantes
            heading="Invitados Persona Moral"
            :solicitantes="collect($invitadoArray)->where('persona', 'moral')"
            tipo="invitado"
        />
    @endif
    @if ($materia === "familiar")
    <x-convenio.tabla-participantes
        heading="Invitados"
        :solicitantes="$invitadoArray"
        tipo="invitado"
    />
    @endif
@endif

{{--* Modal para visualizar datos extras --}}
@if (!empty($detalleSeleccionado))
    <x-convenio.modal-participantes 
        heading="Invitado"
        :detalleSeleccionado="$detalleSeleccionado" 
        :modoEdicion="$modoEdicion"
        :materia="$materia"
    />
@endif



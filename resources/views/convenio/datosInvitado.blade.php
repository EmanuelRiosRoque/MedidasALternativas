{{--* Radio para manejar tipo persona --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative">
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
</div>

{{--* Formularios por TIPO PERSONA --}}
@if (!empty($persona) && $persona === 'fisica')
    @include('convenio.formulario.personaFisica', ['prefix' => 'invitado', 'key' => 'fisica'])
@elseif (!empty($persona) && $persona === 'moral')
    @include('convenio.formulario.personaMoral', ['prefix' => 'invitado', 'key' => 'moral'])
@endif

@if ($materia === 'familiar')
    @include('convenio.formulario.materiaFamiliar', ['prefix' => 'invitado', 'key' => 'familiar'])
@endif

{{--* Dropzone y representante --}}
@include('convenio.complements.datosGeneralesDropzones', ['prefix' => 'invitado'])

{{--** Boton para enviar datos --}}
{{-- <div class="flex justify-end mt-2">
    <button 
        wire:click="{{ $modoEdicion ? 'editarPersona' : "agregarPersona('invitado')" }}"
        type="button"
        class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
    >
        {{ $modoEdicion ? 'Actualizar' : 'Agregar' }}
    </button>
</div> --}}

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



{{--* Radio para manejar tipo persona --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
</div>

{{--* Formularios por TIPO PERSONA --}}
@if ($persona === 'fisica')
    @include('convenio.formulario.personaFisica', ['key' => 'fisica'])
@elseif ($persona === 'moral')
    @include('convenio.formulario.personaMoral', ['key' => 'moral'])
@endif

{{-- TODO: Manejar logica de datos familiares  --}}
@if ($materia === 'familiar')
    @include('convenio.formulario.materiaFamiliar')
@endif

{{--* Dropzone y representante --}}
@include('convenio.complements.datosGeneralesDropzones')

{{--** Boton para enviar datos --}}
<div class="flex justify-end">
    <button 
        wire:click="{{ $modoEdicion ? 'editarPersona' : "agregarPersona('solicitante')" }}"
        type="button"
        class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
    >
        {{ $modoEdicion ? 'Actualizar' : 'Agregar' }}
    </button>
</div>

{{--* Cards donde se visualizan los participantes agregados --}}
@if (!empty($solicitanteArray))
    @if(collect($solicitanteArray)->where('persona', 'fisica')->count())
        <x-convenio.tabla-participantes
            heading="Solicitante Persona Física"
            :solicitantes="collect($solicitanteArray)->where('persona', 'fisica')"
            tipo="solicitante"
        />
    @endif

    @if(collect($solicitanteArray)->where('persona', 'moral')->count())
        <x-convenio.tabla-participantes
            heading="Solicitante Persona Moral"
            :solicitantes="collect($solicitanteArray)->where('persona', 'moral')"
            tipo="solicitante"
        />
    @endif
@endif

{{--* Modal para visualizar datos extras --}}
@if (!empty($detalleSeleccionado))
    <x-convenio.modal-participantes 
        :detalleSeleccionado="$detalleSeleccionado" 
        :modoEdicion="$modoEdicion" 
    />
@endif



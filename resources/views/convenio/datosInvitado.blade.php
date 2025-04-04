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
</div>


@if (!empty($persona) && $persona === 'fisica')
    @include('convenio.formulario.personaFisica', ['prefix' => 'invitado', 'key' => 'fisica'])
@elseif (!empty($persona) && $persona === 'moral')
    @include('convenio.formulario.personaMoral', ['prefix' => 'invitado', 'key' => 'moral'])
@endif


@if ($materia === 'familiar')
    @include('convenio.formulario.materiaFamiliar')
@endif

<div class="flex justify-end">
    <button 
        wire:click="{{ $modoEdicion ? 'editarPersona' : "agregarPersona('invitado')" }}"
        type="button"
        class=" bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
    >
        {{ $modoEdicion ? 'Actualizar' : 'Agregar' }}
    </button>
</div>
{{-- Tabla donde se visualizan los participantes agregados --}}
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
@endif

@if (!empty($detalleSeleccionado))
    <x-convenio.modal-participantes 
        :detalleSeleccionado="$detalleSeleccionado" 
        :modoEdicion="$modoEdicion" 
    />
@endif

{{-- <div class="mt-5 mb-2 animate__animated animate__fadeIn">
    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
        Identificacion
    </label>
    <x-filepond::upload wire:model="identificacion" />
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-2"> 
<flux:radio.group wire:model.live="representante" label="Es ustede el representante?">
    <flux:radio value="1" label="Si" />
    <flux:radio value="0" label="No" />
</flux:radio.group>

    @if ($representante == 1)
    <div class="mt-5 mb-2 animate__animated animate__fadeIn col-span-3">
        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
            Acta notarial
        </label>
        <x-filepond::upload wire:model="acta_notarial" />
    </div>
    @endif
</div> --}}
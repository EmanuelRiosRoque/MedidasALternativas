<div>
    {{--* Radio para manejar tipo persona --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class=" flex justify-center col-span-2">
        <h1 class="text-2xl border-b-2 border-emerald-600">Solicitante</h1>
    </div>

    <div class="flex flex-wrap gap-x-6">
        @if ($materia === 'mercantil')
        <div class="animate__animated animate__fadeIn">
            <flux:radio.group wire:model.live="persona" label="Persona" >
                <flux:radio value="fisica" label="Física" />
                <flux:radio value="moral" label="Moral" />
            </flux:radio.group>
        </div>
        @endif
        <div class="animate__animated animate__fadeIn">
            <flux:radio.group wire:model.live="acudiran_juntos" label="¿Acudirán juntos?" >
                <flux:radio value="1" label="Si" />
                <flux:radio value="2" label="No" />
            </flux:radio.group>
        </div>
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
@if ($persona === 'fisica')
    @include('convenio.formulario.personaFisica', ['prefix' => 'solicitante', 'key' => 'fisica'])
@elseif ($persona === 'moral')
    @include('convenio.formulario.personaMoral', ['prefix' => 'solicitante', 'key' => 'moral'])
@endif

@if ($materia === 'familiar')
    @include('convenio.formulario.materiaFamiliar', ['prefix' => 'solicitante', 'key' => 'familiar'])
@endif

{{--* Dropzone y representante --}}
@include('convenio.complements.datosGeneralesDropzones', ['prefix' => 'solicitante'])

{{--** Boton para enviar datos --}}
{{-- <div class="flex justify-end">
    <button 
        wire:click="{{ $modoEdicion ? 'editarPersona' : "agregarPersona('solicitante')" }}"
        type="button"
        class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 mt-5 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
    >
        {{ $modoEdicion ? 'Actualizar' : 'Agregar' }}
    </button>
</div> --}}

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

    @if ($materia === "familiar")
    <x-convenio.tabla-participantes
        heading="Solicitantes"
        :solicitantes="$solicitanteArray"
        tipo="solicitante"
    />
    @endif
@endif

{{--* Modal para visualizar datos extras --}}
@if (!empty($detalleSeleccionado))
    <x-convenio.modal-participantes 
        heading="Solicitante"
        :detalleSeleccionado="$detalleSeleccionado" 
        :modoEdicion="$modoEdicion" 
        :materia="$materia"
    />
@endif



</div>

@php
    $showPersonaRadio = in_array($materia, ['civil', 'mercantil'], true);

    // Precomputo para tablas
    $sols       = collect($solicitanteArray ?? []);
    $solFisicas = $sols->where('persona', 'fisica');
    $solMorales = $sols->where('persona', 'moral');
@endphp

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex justify-center col-span-2">
            <h1 class="text-2xl border-b-2 border-emerald-600">Solicitante</h1>
        </div>

        {{-- Radios --}}
        <div class="flex flex-wrap gap-x-6 gap-y-4">
            @if ($showPersonaRadio)
                <flux:radio.group wire:model.live="persona" label="Persona">
                    <flux:radio value="fisica" label="Física" />
                    <flux:radio value="moral"  label="Moral" />
                </flux:radio.group>
            @endif

            <flux:radio.group wire:model="acudiran_juntos" label="¿Acudirán juntos?">
                <flux:radio value="1" label="Sí" />
                <flux:radio value="0" label="No" />
            </flux:radio.group>
        </div>

        {{-- ¿Cómo se enteró? --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">¿Cómo se enteró?</label>
            <flux:select wire:model="como_se_entero" placeholder="¿Cómo se enteró?">
                @foreach ($mediosInvitado as $medio)
                    <flux:select.option :value="$medio">{{ $medio }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>

        {{-- Loader solo cuando cambia "persona" --}}
        <div class="w-full flex justify-center col-span-2">
            <div wire:loading.delay.shortest wire:target="persona">
                <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-emerald-600"></div>
            </div>
        </div>
    </div>

    {{-- Formularios por tipo de persona --}}
    <div wire:loading.remove wire:target="persona" class="space-y-6" wire:key="bloque-formularios-{{ $persona ?? 'none' }}">
        @if ($persona === 'fisica')
            <div wire:key="form-fisica" class="transition-opacity duration-150">
                @include('convenio.formulario.personaFisica', ['prefix' => 'solicitante', 'key' => 'fisica'])
            </div>
        @elseif ($persona === 'moral')
            <div wire:key="form-moral" class="transition-opacity duration-150">
                @include('convenio.formulario.personaMoral', ['prefix' => 'solicitante', 'key' => 'moral'])
            </div>
        @endif
    </div>

    {{-- Campos adicionales por materia --}}
    @if ($materia === 'familiar')
        <div wire:key="form-familiar">
            @include('convenio.formulario.materiaFamiliar', ['prefix' => 'solicitante', 'key' => 'familiar'])
        </div>
    @endif

    {{-- Dropzones y representante (complementos) --}}
    @include('convenio.complements.datosComplementarios', ['prefix' => 'solicitante'])

    {{-- Acción principal (dos botones para mayor claridad y evitar interpolaciones en wire:click) --}}
    <div class="flex justify-end gap-3">
        @if ($modoEdicion)
            <flux:button wire:click="editarPersona" variant="primary" type="button" wire:key="btn-actualizar">
                Actualizar
            </flux:button>
        @else
            <flux:button wire:click="agregarPersona('solicitante')" variant="primary" type="button" wire:key="btn-agregar">
                Agregar
            </flux:button>
        @endif
    </div>

    {{-- Tablas de participantes --}}
    @if ($sols->isNotEmpty())
        @if ($solFisicas->count())
            <x-convenio.tabla-participantes
                heading="Solicitante Persona Física"
                :solicitantes="$solFisicas"
                tipo="solicitante"
                wire:key="tabla-solicitante-fisica"
            />
        @endif

        @if ($solMorales->count())
            <x-convenio.tabla-participantes
                heading="Solicitante Persona Moral"
                :solicitantes="$solMorales"
                tipo="solicitante"
                wire:key="tabla-solicitante-moral"
            />
        @endif

        @if ($materia === 'familiar')
            <x-convenio.tabla-participantes
                heading="Solicitantes"
                :solicitantes="$sols"
                tipo="solicitante"
                wire:key="tabla-solicitante-familiar"
            />
        @endif
    @endif

    {{-- Modal edición --}}
    @if (!empty($detalleSeleccionado))
        <x-convenio.modal-participantes
            heading="Solicitante"
            :detalleSeleccionado="$detalleSeleccionado"
            :modoEdicion="$modoEdicion"
            :materia="$materia"
            wire:key="modal-solicitante"
        />
    @endif

</div>

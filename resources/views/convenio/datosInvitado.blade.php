@php
    // Mostrar radio sólo si aplica
    $showPersonaRadio = in_array($materia, ['civil', 'mercantil'], true);

    // Precomputo de invitados
    $invs       = collect($invitadoArray ?? []);
    $invFisicas = $invs->where('persona', 'fisica');
    $invMorales = $invs->where('persona', 'moral');
@endphp

<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="flex justify-center col-span-2">
            <h1 class="text-2xl border-b-2 border-emerald-600">Invitado</h1>
        </div>

        {{-- Radios --}}
        <div class="flex flex-wrap gap-x-6 gap-y-4">
            @if ($showPersonaRadio)
                <flux:radio.group wire:model.live="persona" label="Persona">
                    <flux:radio value="fisica" label="Física" />
                    <flux:radio value="moral"  label="Moral" />
                </flux:radio.group>
            @endif
        </div>

        {{-- ¿Cómo se enteró? --}}
        <div>
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                ¿Cómo se enteró?
            </label>
           <flux:select wire:model="como_se_entero" placeholder="¿Cómo se enteró?">
                @foreach ($mediosInvitado as $id => $nombre)
                    <flux:select.option value="{{ $id }}">
                        {{ $nombre }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>

        {{-- Loader persona --}}
        <div class="w-full flex justify-center col-span-2">
            <div wire:loading.delay.shortest wire:target="persona">
                <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-emerald-600"></div>
            </div>
        </div>
    </div>

    {{-- Formularios dinámicos --}}
    <div wire:loading.remove wire:target="persona" class="space-y-6" wire:key="bloque-invitado-{{ $persona ?? 'none' }}">
        @if ($persona === 'fisica')
            <div wire:key="form-invitado-fisica" class="transition-opacity duration-150">
                @include('convenio.formulario.personaFisica', ['prefix' => 'invitado', 'key' => 'fisica'])
            </div>
        @elseif ($persona === 'moral')
            <div wire:key="form-invitado-moral" class="transition-opacity duration-150">
                @include('convenio.formulario.personaMoral', ['prefix' => 'invitado', 'key' => 'moral'])
            </div>
        @endif
    </div>

    {{-- Campos adicionales por materia --}}
    @if ($materia === 'familiar')
        <div wire:key="form-invitado-familiar">
            @include('convenio.formulario.materiaFamiliar', ['prefix' => 'invitado', 'key' => 'familiar'])
        </div>
    @endif

    {{-- Dropzones --}}
    @include('convenio.complements.datosComplementarios', ['prefix' => 'invitado'])

    {{-- Botón acción --}}
    <div class="flex justify-end gap-3">
        @if ($modoEdicion)
            <flux:button wire:click="editarPersona" variant="primary" type="button" wire:key="btn-invitado-actualizar">
                Actualizar
            </flux:button>
        @else
            <flux:button wire:click="agregarPersona('invitado')" variant="primary" type="button" wire:key="btn-invitado-agregar">
                Agregar
            </flux:button>
        @endif
    </div>

    {{-- Tablas de participantes --}}
    @if ($invs->isNotEmpty())
        @if ($invFisicas->count())
            <x-convenio.tabla-participantes
                heading="Invitados Persona Física"
                :solicitantes="$invFisicas"
                tipo="invitado"
                wire:key="tabla-invitado-fisica"
            />
        @endif

        @if ($invMorales->count())
            <x-convenio.tabla-participantes
                heading="Invitados Persona Moral"
                :solicitantes="$invMorales"
                tipo="invitado"
                wire:key="tabla-invitado-moral"
            />
        @endif

        @if ($materia === 'familiar')
            <x-convenio.tabla-participantes
                heading="Invitados"
                :solicitantes="$invs"
                tipo="invitado"
                wire:key="tabla-invitado-familiar"
            />
        @endif
    @endif

    {{-- Modal detalle --}}
    @if (!empty($detalleSeleccionado))
        <x-convenio.modal-participantes
            heading="Invitado"
            :detalleSeleccionado="$detalleSeleccionado"
            :modoEdicion="$modoEdicion"
            :materia="$materia"
            wire:key="modal-invitado"
        />
    @endif
</div>

@php
    $tabs = [
        1 => ['label' => 'Datos generales',  'icon' => 'document',   'view' => 'convenio.datosGenerales'],
        2 => ['label' => 'Solicitante',      'icon' => 'hand-raised','view' => 'convenio.datosSolicitante'],
        3 => ['label' => 'Invitado',         'icon' => 'users',      'view' => 'convenio.datosInvitado'],
        4 => ['label' => 'Documentos',       'icon' => 'paper-clip', 'view' => 'convenio.combosDocumentos'],
    ];
    $maxTab = count($tabs);
@endphp

<div class="relative h-full px-4 overflow-hidden dark:from-neutral-800 dark:to-neutral-900">
    <div class="relative z-10 w-full max-w-5xl mx-auto mt-2 mb-2 rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">Exito!</span> {{ session('success') }}.
            </div>
        @endif
        <flux:navbar class="mb-6 justify-center" role="tablist" aria-label="Pestañas de convenio">
            @foreach ($tabs as $id => $tabDef)
                <flux:navbar.item
                    :wire:key="'tab-item-' . $id"
                    wire:click.prevent="cambiarTab({{ $id }})"
                    icon="{{ $tabDef['icon'] }}"
                    :current="$tab === $id"
                    role="tab"
                    aria-selected="{{ $tab === $id ? 'true' : 'false' }}"
                    aria-controls="panel-tab-{{ $id }}"
                    id="tab-{{ $id }}"
                >
                    {{ $tabDef['label'] }}
                </flux:navbar.item>
            @endforeach
        </flux:navbar>

        <div wire:loading.delay wire:target="cambiarTab" class="mb-6 w-full mx-auto" aria-live="polite">
            @include('components.convenio.includes.skeleton-loader')
        </div>

        <div
            class="p-4 rounded-md dark:bg-neutral-900"
            wire:loading.remove
            wire:target="cambiarTab"
            wire:key="tab-panel-{{ $tab }}"
            id="panel-tab-{{ $tab }}"
            role="tabpanel"
            aria-labelledby="tab-{{ $tab }}"
        >
            @includeIf($tabs[$tab]['view'] ?? null)
        </div>

        <!-- Navegación inferior -->
        <div class="mt-10 pt-6 border-t border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
            @if ($tab > 1)
                <flux:button
                    :wire:key="'btn-prev-' . $tab"
                    wire:click="cambiarTab({{ $tab - 1 }})"
                    icon="arrow-left"
                >
                    Anterior
                </flux:button>
            @else
                <div></div>
            @endif

            @if ($tab < $maxTab)
                <flux:button
                    :wire:key="'btn-next-' . $tab"
                    wire:click="cambiarTab({{ $tab + 1 }})"
                    icon:trailing="arrow-right"
                    variant="primary"
                >
                    Siguiente
                </flux:button>
            @endif

            @if ($tab === $maxTab)
                <flux:button
                    wire:key="btn-guardar"
                    wire:click="guardado"
                    variant="primary"
                >
                    Guardar
                </flux:button>
            @endif
        </div>
    </div>
</div>

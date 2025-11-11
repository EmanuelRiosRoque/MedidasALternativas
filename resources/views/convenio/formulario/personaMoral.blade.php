@props(['prefix'])

<div class="gap-4 mt-2 animate__animated animate__fadeIn" wire:key="{{ $key }}">
    <div class="grid grid-cols-2 gap-4">
        {{-- Razón social --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Razón social
                    *
            </label>
            <flux:input
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
                wire:model="razon_social_solicitante"
                type="text"
                required
                placeholder="Razón social"
            />
        </div>
    
        {{-- RFC --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                RFC
            </label>
            <flux:input
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
                wire:model="rfc_solicitante"
                type="text"
                required
                placeholder="RFC"
            />
        </div>

        {{-- Instrumento notarial --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Instrumento notarial 
                @if($prefix === 'solicitante')
                    *
                @endif
                <flux:tooltip toggleable>
                    <flux:button icon="information-circle" size="sm" variant="ghost" />
                    <flux:tooltip.content class="max-w-[20rem] space-y-2">
                        <p>Número de Instrumento Notarial</p>
                        <p>Nombre de la Autoridad Fedataria</p>
                        <p>Póliza</p>
                        <p>Título de Crédito</p>
                    </flux:tooltip.content>
                </flux:tooltip>

            </label>
            <flux:input
                oninput="this.value = this.value
                    .toUpperCase()
                    .replace(/[ÁÀÂÄ]/g,'A')
                    .replace(/[ÉÈÊË]/g,'E')
                    .replace(/[ÍÌÎÏ]/g,'I')
                    .replace(/[ÓÒÔÖ]/g,'O')
                    .replace(/[ÚÙÛÜ]/g,'U')"
                wire:model="instrumento_solicitante"
                type="text"
                required
                placeholder="Instrumento notarial"
            />
        </div>

        {{-- Fecha del instrumento --}}
        <div class="space-y-1">
            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">
                Fecha del Instrumento Notarial
                @if($prefix === 'solicitante')
                    *
                @endif
            </label>
                <x-ui.date
                    name="fecha_instrumento_solicitante"
                    wire:model="fecha_instrumento_solicitante"
                />
          </div>
        </div>

    </div>
    @include('components.ui.datos-contacto')

    @include('components.ui.domicilios')

</div>
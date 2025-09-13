<div class="grid grid-cols-1 md:grid-cols-3 md:gap-1 gap-2">

    {{-- Solicitantes --}}
    <x-lista-personas :$solicitudId :personas="$solicitantes" titulo="Solicitantes" />

    {{-- Facilitador / Co-mediador --}}
    <div class="mx-auto right-0 mt-9 w-60">
        @php
            // Detecta co-mediador por relación camelCase o snake_case
            $coNombre = null;
            $coId = $solicitud->co_mediador_id ?? null;

            if (isset($solicitud->coMediador) && $solicitud->coMediador) {
                $coNombre = $solicitud->coMediador->nombre;
                $coId = $solicitud->co_mediador_id ?? $solicitud->coMediador->id ?? $coId;
            } elseif (isset($solicitud->co_mediador) && $solicitud->co_mediador) {
                $coNombre = $solicitud->co_mediador->nombre;
                $coId = $solicitud->co_mediador_id ?? $solicitud->co_mediador->id ?? $coId;
            }
        @endphp

        <div class="shadow-lg">
            <div
                class="text-center p-4 w-full max-w-md bg-white dark:bg-neutral-800 rounded-lg shadow-md sm:p-8 border border-neutral-300 dark:border-neutral-700 transform transition duration-300 ease-in-out hover:scale-[1.02]">

                {{-- Avatar --}}
                <svg aria-hidden="true" role="img"
                    class="h-24 w-24 text-neutral-300 dark:text-white rounded-full mx-auto" width="32" height="32"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                    <path fill="currentColor"
                        d="M172 120a44 44 0 1 1-44-44a44 44 0 0 1 44 44Zm60 8A104 104 0 1 1 128 24a104.2 104.2 0 0 1 104 104Zm-16 0a88 88 0 1 0-153.8 58.4a81.3 81.3 0 0 1 24.5-23a59.7 59.7 0 0 0 82.6 0a81.3 81.3 0 0 1 24.5 23A87.6 87.6 0 0 0 216 128Z">
                    </path>
                </svg>

                {{-- Facilitador --}}
                <p class="pt-2 text-lg font-semibold text-neutral-700 dark:text-neutral-50">
                    {{ $solicitud->facilitador->nombre ?? 'Sin asignar' }}
                </p>
                <p class="pt-1 text-xs font-medium text-neutral-500 dark:text-neutral-300">
                    Facilitador
                </p>

                {{-- Co-mediador (si ya existe) --}}
                @if ($solicitud->co_mediador_id)
                    <div class="mt-4 rounded-md border border-emerald-200 dark:border-emerald-700/40 bg-emerald-50/60 dark:bg-emerald-900/10 p-3">
                        <p class="text-xs uppercase tracking-wide text-emerald-700 dark:text-emerald-300 font-semibold">Co-mediador</p>
                        <p class="text-sm font-medium text-neutral-800 dark:text-neutral-100">{{  $solicitud->coMediador->nombre ?? 'Sin co-mediador' }}</p>
                    </div>
                @endif

                {{-- Botón + modal: solo si está en Mediación y NO hay co-mediador aún --}}
                @if ($solicitud->tipo_proceso_id == 2 && !$coId)
                    <div class="mt-4">
                        <flux:modal.trigger name="co-mediador">
                            <flux:button variant="primary">
                                Agregar co-mediador
                            </flux:button>
                        </flux:modal.trigger>
                    </div>

                    <flux:modal name="co-mediador" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Elige al co-mediador</flux:heading>
                                <flux:text class="mt-2">Selecciona un facilitador para asignarlo como co-mediador de esta solicitud.</flux:text>
                            </div>

                            {{-- Select dinámico de facilitadores --}}
                            <flux:select wire:model="coMediadorId" placeholder="Seleccione un co-mediador...">
                                @forelse ($facilitadores as $fac)
                                    <flux:select.option value="{{ $fac->id }}">{{ $fac->nombre }}</flux:select.option>
                                @empty
                                    <flux:select.option disabled>No hay facilitadores disponibles</flux:select.option>
                                @endforelse
                            </flux:select>
                            @error('coMediadorId')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror

                            <div class="flex">
                                <flux:spacer />
                                <flux:button wire:click="guardarCoMediador" variant="primary">
                                    Guardar
                                </flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            </div>
        </div>
    </div>

    {{-- Invitados --}}
    <x-lista-personas :$solicitudId :personas="$invitados" titulo="Invitados" />
</div>

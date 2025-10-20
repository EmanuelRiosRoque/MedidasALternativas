{{-- Cuerpo con espaciado entre bloques (2 columnas) --}}
<div class="p-6" x-data="{ deleteId: null }" x-on:delete:set.window="deleteId = $event.detail.id">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- Columna izquierda: FORMULARIO (sticky) --}}
        <div class="md:col-span-4">
            <div
                class="space-y-4 rounded-xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-md p-4 md:sticky md:top-4">

                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        Fecha de observación
                    </label>
                    <div class="sm:col-span-3">
                        <flux:input type="date" wire:model.defer="obs.fecha_observacion" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                        Observaciones
                    </label>
                    <textarea wire:model.defer="obs.observacion" rows="4"
                        class="w-full rounded-lg bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 outline-none focus:ring-2 focus:ring-teal-400/60"></textarea>
                </div>

                <div class="flex gap-2">
                    <flux:button type="button" size="sm" variant="primary" wire:click='agregarObservacion'>
                        Agregar observación
                    </flux:button>
                </div>
            </div>
        </div>

        {{-- Columna derecha: LISTA --}}
        <div class="md:col-span-8">
            <div class="rounded-xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-md text-xs overflow-hidden">
                <!-- Topbar -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-neutral-200 dark:border-neutral-800">
                    <h3 class="text-[13px] font-semibold text-neutral-800 dark:text-neutral-100 flex items-center gap-2">
                        <span
                            class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-teal-100 text-teal-700 dark:bg-teal-500/20 dark:text-teal-200">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3h18v14H3z" />
                                <path d="M8 21h8" />
                                <path d="M12 17v4" />
                            </svg>
                        </span>
                        Observaciones registradas
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-200 dark:ring-emerald-500/30">
                            {{ $observaciones->count() }}
                        </span>
                    </h3>
                </div>

                <div class="p-4">
                    @if($observaciones->isEmpty())
                        <!-- Empty -->
                        <div class="flex flex-col items-center justify-center text-center py-14">
                            <div
                                class="flex items-center justify-center h-10 w-10 rounded-full bg-teal-100 text-teal-700 ring-1 ring-teal-200 dark:bg-teal-500/20 dark:text-teal-200 dark:ring-teal-500/30 mb-3">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 3h18v14H3z" />
                                    <path d="M8 21h8" />
                                    <path d="M12 17v4" />
                                </svg>
                            </div>
                            <p class="text-[13px] text-neutral-600 dark:text-neutral-300">No hay observaciones registradas.</p>
                        </div>
                    @else
                        <!-- LISTA EN ACORDEÓN CON SCROLL -->
                        <div class="space-y-2 max-h-[320px] overflow-y-auto pr-2
                                    scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent
                                    dark:scrollbar-thumb-neutral-700 dark:scrollbar-track-transparent">

                            @foreach($observaciones as $o)
                                @php $idx = $o->id; @endphp

                                <div wire:key="obs-{{ $o->id }}" x-data="accordion({{ $idx }})"
                                    class="rounded-lg border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 overflow-hidden
                                           hover:border-emerald-400 dark:hover:border-emerald-600/60 transition-colors">

                                    <!-- Header -->
                                    <button type="button" @click="handleClick()" class="w-full px-3 py-2 text-left">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md
                                                           bg-teal-100 text-teal-800 ring-1 ring-teal-200
                                                           dark:bg-teal-500/15 dark:text-teal-200 dark:ring-teal-500/30">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 0 0 2-2V7H3v12a2 2 0 0 0 2 2z" />
                                                    </svg>
                                                    {{ $o->fecha_observacion ? \Carbon\Carbon::parse($o->fecha_observacion)->format('d/m/Y') : '—' }}
                                                </span>
                                            </div>

                                            <!-- caret -->
                                            <span :class="handleRotate()" class="shrink-0 transition-transform duration-300">
                                                <svg class="w-4 h-4 text-neutral-700 dark:text-neutral-300"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                                </svg>
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Body -->
                                    <div x-ref="tab" :style="handleToggle()" class="relative overflow-hidden transition-all duration-500 max-h-0">
                                        <div class="px-3 pb-3 pt-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="text-neutral-800 dark:text-neutral-100 leading-5 min-w-0">
                                                    {{ $o->observacion }}
                                                </div>

                                                <div class="shrink-0">
                                                    {{-- Dispara el modal global y pasa el ID por evento --}}
                                                    <flux:modal.trigger name="confirm-delete"
                                                        @click="$dispatch('delete:set', { id: {{ $o->id }} })">
                                                        <flux:button type="button" size="xs" variant="ghost"
                                                            class="text-red-600 hover:text-white hover:bg-red-600/90 focus:ring-2 focus:ring-red-200
                                                                   dark:text-red-400 dark:hover:text-white dark:hover:bg-red-600/80 dark:focus:ring-red-500/30"
                                                            title="Eliminar observación">
                                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                                                 stroke="currentColor" stroke-width="1.8"
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6L6 18M6 6l12 12" />
                                                            </svg>
                                                        </flux:button>
                                                    </flux:modal.trigger>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== MODAL GLOBAL DE CONFIRMACIÓN ===================== --}}
    <flux:modal name="confirm-delete" class="min-w-[22rem]">
        <div class="space-y-4">
            <div class="flex items-start gap-2">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full
                           bg-red-100 text-red-700 ring-1 ring-red-200
                           dark:bg-red-500/20 dark:text-red-200 dark:ring-red-500/30 mt-0.5">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4M12 17h.01" />
                        <path d="M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                    </svg>
                </span>
                <div>
                    <flux:heading size="md">¿Eliminar observación?</flux:heading>
                    <flux:text class="mt-1"><p>Esta acción no puede deshacerse.</p></flux:text>
                </div>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost" size="sm"
                        class="!text-neutral-700 hover:!bg-neutral-100 dark:!text-neutral-200 dark:hover:!bg-neutral-800">
                        Cancelar
                    </flux:button>
                </flux:modal.close>

                {{-- Llamamos al método Livewire usando el id guardado en Alpine --}}
                <flux:modal.close>
                    <flux:button variant="danger" size="sm"
                        class="!bg-red-600 hover:!bg-red-700 !text-white focus:!ring-2 focus:!ring-red-200 dark:focus:!ring-red-500/30"
                        x-on:click="$wire.borrarObservacion(deleteId)">
                        Eliminar
                    </flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>

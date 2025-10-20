{{-- ===== PRIMERA INVITACIÓN ===== --}}
<div class="space-y-6">
    <div
        class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
            <span
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                <flux:icon.calendar class="size-4" />
                Primera invitación
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- SOLICITANTES - 1RA --}}
        <div
            class="md:col-span-3 h-full rounded-xl p-5 ring-1 ring-blue-200/60 dark:ring-blue-700/40 bg-blue-50/40 dark:bg-blue-900/10 border-l-4 border-blue-500 space-y-4">
            <div class="mb-2">
                <span class="inline-flex items-center gap-2 rounded-full
                     bg-blue-100 text-blue-800
                     dark:bg-blue-400/20 dark:text-blue-100
                     ring-1 ring-blue-200 dark:ring-blue-500/30
                     px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Solicitantes
                </span>
            </div>

            {{-- 🌿 Scroll moderno --}}
            <div class="space-y-2 max-h-[242px] overflow-y-auto pr-2
                        scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent
                        dark:scrollbar-thumb-neutral-700 dark:scrollbar-track-transparent">

                @foreach ($solicitantes as $s)
                <div class="rounded-lg border border-blue-200 dark:border-blue-700 bg-white dark:bg-neutral-900 p-4"
                    wire:key="primera-sol-{{ $s->id }}">
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-2">
                        {{ $s->nombre }}
                    </p>

                    <div class="space-y-4">
                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label
                                class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                Fecha de envío
                            </label>
                            <div class="sm:col-span-3">
                                <flux:input type="date"
                                    wire:model.defer="primera.detalle_solicitante.{{ $s->id }}.fecha_envio" />
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label
                                class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                Medio de envío
                            </label>
                            <div class="sm:col-span-3">
                                <flux:radio.group wire:model="primera.detalle_solicitante.{{ $s->id }}.medio_envio">
                                    <flux:radio value="personal" label="Personal" />
                                    <flux:radio value="sepomex" label="SEPOMEX" />
                                </flux:radio.group>
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label
                                class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                ¿Acepta la mediación?
                            </label>
                            <div class="sm:col-span-3">
                                <flux:radio.group
                                    wire:model="primera.detalle_solicitante.{{ $s->id }}.acepta_mediacion">
                                    <flux:radio :value="1" label="Sí" />
                                    <flux:radio :value="0" label="No" />
                                </flux:radio.group>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- INVITADOS - 1RA --}}
        <div
            class="md:col-span-9 h-full rounded-xl p-5 ring-1 ring-emerald-200/60 dark:ring-emerald-700/40 bg-emerald-50/60 dark:bg-emerald-900/10 border-l-4 border-emerald-500 space-y-6">
            <div class="mb-2">
                <span class="inline-flex items-center gap-2 rounded-full
                     bg-emerald-100 text-emerald-800
                     dark:bg-emerald-400/20 dark:text-emerald-100
                     ring-1 ring-emerald-200 dark:ring-emerald-500/30
                     px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Invitados
                </span>
            </div>

            {{-- 🌿 Scroll moderno --}}
            <div class="space-y-3 max-h-[250px] overflow-y-auto pr-2
                        scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent
                        dark:scrollbar-thumb-neutral-700 dark:scrollbar-track-transparent">

                @foreach ($invitados as $i)
                <div class="rounded-lg border border-emerald-200 dark:border-emerald-700 bg-white dark:bg-neutral-900 p-4"
                    wire:key="primera-inv-{{ $i->id }}">
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                        {{ $i->nombre }}
                    </p>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Col 1: Programación --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <flux:icon.clock class="size-4" /> Programación
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Se
                                        le espera el día</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="date"
                                            wire:model.defer="primera.detalle_invitado.{{ $i->id }}.fecha_sele_espera" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="time"
                                            wire:model.defer="primera.detalle_invitado.{{ $i->id }}.hora_sele_espera" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Atendió
                                        a la 1ra sesión</label>
                                    <div class="sm:col-span-3">
                                        <flux:radio.group
                                            wire:model="primera.detalle_invitado.{{ $i->id }}.atendio_sesion">
                                            <flux:radio :value="1" label="Sí" />
                                            <flux:radio :value="0" label="No" />
                                        </flux:radio.group>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Col 2: Asistencia real --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <flux:icon.clipboard-document-check class="size-4" /> Asistencia real
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Fecha
                                        en que asiste</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="date"
                                            wire:model.defer="primera.detalle_invitado.{{ $i->id }}.fecha_asistencia" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="time"
                                            wire:model.defer="primera.detalle_invitado.{{ $i->id }}.hora_asistencia" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta
                                        la mediación?</label>
                                    <div class="sm:col-span-3">
                                        <flux:radio.group
                                            wire:model="primera.detalle_invitado.{{ $i->id }}.acepta_mediacion_inv">
                                            <flux:radio :value="1" label="Sí" />
                                            <flux:radio :value="0" label="No" />
                                        </flux:radio.group>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Col 3: Datos --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <svg class="h-4 w-4 text-emerald-500 dark:text-emerald-300" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M5 12l5 5L20 7" />
                                </svg>
                                Datos
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                <span class="font-medium">Invitado:</span> {{ $i->nombre }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>



{{-- ===== SEGUNDA INVITACIÓN ===== --}}
<div class="space-y-6">
    <div
        class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
            <span
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                <flux:icon.calendar class="size-4" />
                Segunda invitación
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- SOLICITANTES - 2DA --}}
        <div
            class="md:col-span-3 h-full rounded-xl p-5 ring-1 ring-blue-200/60 dark:ring-blue-700/40 bg-blue-50/40 dark:bg-blue-900/10 border-l-4 border-blue-500 space-y-4">
            <div class="mb-2">
                <span class="inline-flex items-center gap-2 rounded-full
                       bg-blue-100 text-blue-800
                       dark:bg-blue-400/20 dark:text-blue-100
                       ring-1 ring-blue-200 dark:ring-blue-500/30
                       px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Solicitantes
                </span>
            </div>

            {{-- 🌿 Scroll moderno --}}
            <div class="space-y-2 max-h-[242px] overflow-y-auto pr-2
                        scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent
                        dark:scrollbar-thumb-neutral-700 dark:scrollbar-track-transparent">

                @foreach ($solicitantes as $s)
                <div class="rounded-xl border border-blue-200/60 dark:border-blue-700/40 bg-white dark:bg-neutral-900 p-4"
                    wire:key="segunda-sol-{{ $s->id }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">
                            {{ $s->nombre }}
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                Fecha de envío
                            </label>
                            <div class="sm:col-span-3">
                                <flux:input type="date"
                                    wire:model.defer="segunda.detalle_solicitante.{{ $s->id }}.fecha_envio" />
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                Medio de envío
                            </label>
                            <div class="sm:col-span-3">
                                <flux:radio.group wire:model="segunda.detalle_solicitante.{{ $s->id }}.medio_envio">
                                    <flux:radio value="personal" label="Personal" />
                                    <flux:radio value="sepomex" label="SEPOMEX" />
                                </flux:radio.group>
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                            <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                                ¿Acepta la mediación?
                            </label>
                            <div class="sm:col-span-3">
                                <flux:radio.group wire:model="segunda.detalle_solicitante.{{ $s->id }}.acepta_mediacion">
                                    <flux:radio :value="1" label="Sí" />
                                    <flux:radio :value="0" label="No" />
                                </flux:radio.group>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- INVITADOS - 2DA --}}
        <div
            class="md:col-span-9 h-full rounded-xl p-5 ring-1 ring-emerald-200/60 dark:ring-emerald-700/40 bg-emerald-50/60 dark:bg-emerald-900/10 border-l-4 border-emerald-500 space-y-6">
            <div class="mb-2">
                <span class="inline-flex items-center gap-2 rounded-full
                       bg-emerald-100 text-emerald-800
                       dark:bg-emerald-400/20 dark:text-emerald-100
                       ring-1 ring-emerald-200 dark:ring-emerald-500/30
                       px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Invitados
                </span>
            </div>

            {{-- 🌿 Scroll moderno --}}
            <div class="space-y-3 max-h-[252px] overflow-y-auto pr-2
                        scrollbar-thin scrollbar-thumb-neutral-300 scrollbar-track-transparent
                        dark:scrollbar-thumb-neutral-700 dark:scrollbar-track-transparent">

                @foreach ($invitados as $i)
                <div class="rounded-xl border border-emerald-200/60 dark:border-emerald-700/40 bg-white dark:bg-neutral-900 p-4"
                    wire:key="segunda-inv-{{ $i->id }}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">
                            {{ $i->nombre }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- Col 1: Programación --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <flux:icon.clock class="size-4" /> Programación
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Se
                                        le espera el día</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="date"
                                            wire:model.defer="segunda.detalle_invitado.{{ $i->id }}.fecha_sele_espera" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="time"
                                            wire:model.defer="segunda.detalle_invitado.{{ $i->id }}.hora_sele_espera" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Atendió
                                        a la 2da sesión</label>
                                    <div class="sm:col-span-3">
                                        <flux:radio.group
                                            wire:model="segunda.detalle_invitado.{{ $i->id }}.atendio_sesion">
                                            <flux:radio :value="1" label="Sí" />
                                            <flux:radio :value="0" label="No" />
                                        </flux:radio.group>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Col 2: Asistencia real --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <flux:icon.clipboard-document-check class="size-4" /> Asistencia real
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="space-y-4">
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Fecha
                                        en que asiste</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="date"
                                            wire:model.defer="segunda.detalle_invitado.{{ $i->id }}.fecha_asistencia" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                                    <div class="sm:col-span-3">
                                        <flux:input type="time"
                                            wire:model.defer="segunda.detalle_invitado.{{ $i->id }}.hora_asistencia" />
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                                    <label
                                        class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta
                                        la mediación?</label>
                                    <div class="sm:col-span-3">
                                        <flux:radio.group
                                            wire:model="segunda.detalle_invitado.{{ $i->id }}.acepta_mediacion_inv">
                                            <flux:radio :value="1" label="Sí" />
                                            <flux:radio :value="0" label="No" />
                                        </flux:radio.group>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Col 3: Datos --}}
                        <div>
                            <div
                                class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                                <svg class="h-4 w-4 text-emerald-500 dark:text-emerald-300" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M5 12l5 5L20 7" />
                                </svg>
                                Datos
                                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
                            </div>
                            <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                <span class="font-medium">Invitado:</span> {{ $i->nombre }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">
    <div
        class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 rounded-xl
                      bg-red-100 text-red-800
                      dark:bg-red-700/40 dark:text-red-100
                      ring-1 ring-red-200 dark:ring-red-600/40
                      px-3 py-1 text-sm font-semibold">
                Centro de Justicia Alternativa
            </span>
        </div>
    </div>

    {{-- GRID 50/50 --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- CJA: SOLICITANTE --}}
        <div class="md:col-span-6 h-full rounded-xl p-5">
            <div class="mb-3">
                <span class="inline-flex items-center gap-2 rounded-full
                        bg-blue-100 text-blue-800
                        dark:bg-blue-400/20 dark:text-blue-100
                        ring-1 ring-blue-200 dark:ring-blue-500/30
                        px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Solicitante
                </span>
            </div>
            <div class="space-y-3">
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        Propuesta de inicio mediación
                    </label>
                    <div class="sm:col-span-3">
                        <flux:input type="date" wire:model.defer="cja.propuesta_inicio_fecha" />
                    </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        Hora propuesta
                    </label>
                    <div class="sm:col-span-3">
                        <flux:input type="time" wire:model.defer="cja.propuesta_inicio_hora" />
                    </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        ¿Acepta inicio?
                    </label>
                    <div class="sm:col-span-3">
                        <flux:radio.group wire:model="cja.acepta_inicio">
                            <flux:radio :value="1" label="Sí" />
                            <flux:radio :value="0" label="No" />
                        </flux:radio.group>
                    </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        Fecha vencimiento
                    </label>
                    <div class="sm:col-span-3">
                        <flux:input type="date" wire:model.defer="cja.fecha_vencimiento" />
                    </div>
                </div>
            </div>

        </div>

        {{-- CJA: INVITADO --}}
        <div
            class="md:col-span-6 h-full rounded-xl p-5 ring-1 ring-yellow-200/60 dark:ring-yellow-700/40 bg-yellow-50/60 dark:bg-yellow-900/10 border-l-4 border-yellow-500">
            <div class="mb-3">
                <span class="inline-flex items-center gap-2 rounded-full
                        bg-yellow-100 text-yellow-800
                        dark:bg-yellow-400/20 dark:text-yellow-100
                        ring-1 ring-yellow-200 dark:ring-yellow-500/30
                        px-2.5 py-0.5 text-xs font-semibold">
                    <flux:icon.user class="size-4" /> Invitado
                </span>
            </div>

            <div class="space-y-3">
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        Fecha en que es propuesta
                    </label>
                    <div class="sm:col-span-3">
                        <flux:input type="date" wire:model.defer="cja.fecha_propuesta" />
                    </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
                    <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                        ¿Acepta inicio?
                    </label>
                    <div class="sm:col-span-3">
                        <flux:radio.group wire:model="cja.acepta_inicio_inv">
                            <flux:radio :value="1" label="Sí" />
                            <flux:radio :value="0" label="No" />
                        </flux:radio.group>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Header: Tabs izquierda / Documentos derecha -->
<div class="relative z-10 w-full max-w-8xl mb-4">
    <div class="flex items-center justify-between gap-3">

        <!-- Tabs -->
        <div
            class="inline-flex rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white/70 dark:bg-neutral-900/70 backdrop-blur p-1">
            <button type="button" class="px-4 sm:px-6 py-2 rounded-xl text-sm font-semibold transition" :class="tab==='solicitud'
            ? 'bg-emerald-600 text-white shadow'
            : 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800'"
                @click="tab='solicitud'">
                Detalle solicitud
            </button>
            <button type="button" class="px-4 sm:px-6 py-2 rounded-xl text-sm font-semibold transition" :class="tab==='invitaciones'
            ? 'bg-emerald-600 text-white shadow'
            : 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800'"
                @click="tab='invitaciones'">
                Invitaciones / Sesiones
            </button>
        </div>

        <!-- Botón Documentos (arriba derecha) -->
        @if ($solicitud->modalidad == 1)
        <flux:modal.trigger name="documentos">
            <flux:button variant="primary" icon="arrow-down-tray">Documentos</flux:button>
        </flux:modal.trigger>
        @endif
    </div>
</div>
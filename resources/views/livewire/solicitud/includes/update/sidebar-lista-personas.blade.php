<aside
    class="w-80 border-r border-emerald-600/40 bg-white/30 dark:bg-neutral-800/60 backdrop-blur-lg text-white p-4 space-y-4 z-10 overflow-y-auto shadow-md">

    <h2 class="text-base font-semibold uppercase text-emerald-400 tracking-wider mb-4">Personas</h2>

    <!-- Filtros -->
    <div class="flex flex-wrap gap-2">
        <template x-for="tipo in ['todos', 'solicitante', 'invitado']" :key="tipo">
            <button @click="filtro = tipo" class="px-3 py-1 rounded-full text-xs transition border"
                :class="filtro === tipo
                ? 'bg-emerald-500 text-white border-emerald-500'
                : 'bg-white text-neutral-700 border-neutral-300 hover:bg-emerald-100 dark:bg-white/10 dark:text-white dark:border-neutral-700 dark:hover:bg-emerald-500'">
                <span x-text="tipo.charAt(0).toUpperCase() + tipo.slice(1)"></span>
            </button>
        </template>
    </div>


    <!-- Lista de personas -->
    <template x-for="persona in filtradas" :key="persona.id">
        <div @click="$wire.seleccionarPersona(persona.id)"
            class="bg-white/30 dark:bg-neutral-700/40 border border-neutral-200/40 dark:border-neutral-700 rounded-xl p-4 transition-all transform hover:scale-[1.02] hover:ring hover:ring-emerald-400 hover:border-emerald-500/50 cursor-pointer backdrop-blur-sm shadow-sm mb-2">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <flux:icon.user-circle class="size-10 text-emerald-400" />
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-neutral-800 dark:text-white truncate"
                            x-text="persona.nombre ? persona.nombre : persona.razon_social"></p>
                        <template x-if="persona.tipo_solicitante">
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full" :class="{
                                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300': persona.tipo_solicitante === 'invitado',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': persona.tipo_solicitante === 'solicitante',
                                    'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200': !['solicitante', 'invitado'].includes(persona.tipo_solicitante)
                                  }"
                                x-text="(persona.tipo_solicitante || 'desconocido').replace(/^./, c => c.toUpperCase())">
                            </span>
                        </template>
                    </div>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate"
                        x-text="persona.persona.replace(/^./, c => c.toUpperCase()) ?? 'Sin detalles'"></p>
                </div>
            </div>
        </div>
    </template>
</aside>
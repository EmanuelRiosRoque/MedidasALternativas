{{-- Modal de Documentos (teleport automático) --}}
@if ($solicitud->modalidad == 1)
<flux:modal name="documentos" class="w-full max-w-5xl sm:max-w-6xl">
    <div x-data="{ medio_envio:'sepomex', docs:@js($docs) }" class="space-y-6 max-h-[75vh] overflow-y-auto px-1">
        <flux:heading size="lg">Documentos de envío</flux:heading>
        <flux:text class="mt-1">Selecciona el medio y descarga los formatos.</flux:text>

        <flux:radio.group x-model="medio_envio">
            <flux:radio value="sepomex" label="SEPOMEX" />
            <flux:radio value="personal" label="Personal" />
        </flux:radio.group>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
            <template x-for="doc in docs[medio_envio]" :key="doc.label">
                <a :href="doc.url"
                    class="group relative block rounded-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-4 hover:ring-emerald-400/60 hover:shadow-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 transition">
                    <div class="flex items-start gap-3">
                        <div
                            class="shrink-0 mt-0.5 rounded-xl p-3 bg-emerald-100 dark:bg-emerald-900/40 ring-1 ring-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                                <path d="M10 9H8" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-neutral-900 dark:text-white truncate"
                                x-text="doc.label"></h4>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5" x-text="doc.hint"></p>
                        </div>
                        <div class="ml-auto opacity-60 group-hover:opacity-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path d="M12 3v12m0 0 4-4m-4 4-4-4" />
                                <path d="M5 21h14" />
                            </svg>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        <div class="flex pb-2 justify-end">
            <flux:modal.close>
                <flux:button variant="ghost">Cerrar</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
@endif
<main class="flex-1 p-6 z-10 overflow-y-auto "  >
    <div class="max-w-7xl mx-auto space-y-6">
        <div wire:loading.flex wire:target="seleccionarPersona" class="flex items-center justify-center py-10">
            <div class="text-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-400 mx-auto mb-2"></div>
                <p class="text-white text-sm">Cargando datos de la persona...</p>
            </div>
        </div>
        @if ($personaSeleccionada != [])
        <div
            wire:loading.remove wire:target="seleccionarPersona"
            class="animate__animated animate__fadeIn backdrop-blur-lg bg-white/10 dark:bg-neutral-700/30 shadow-lg rounded-lg border border-white/20 dark:border-neutral-600 p-4"
        >
            <h1 class="text-base font-semibold uppercase text-emerald-400 tracking-wider mb-4">Datos editables</h1>

            <div>
    
                <livewire:solicitud.datos-personales 
                    :$personaSeleccionada 
                    :key="'dp-'.$personaSeleccionada['id'].'-'.Str::uuid()" 
                />

                <livewire:solicitud.contacto-persona 
                    :$correos :$telefonos :personaId="$personaSeleccionada['id']"
                    :key="'dp-'.$personaSeleccionada['id'].'-'.Str::uuid()" 
                />

                <livewire:solicitud.documentos-persona 
                    :personaId="$personaSeleccionada['id']"
                    :key="'dp-'.$personaSeleccionada['id'].'-'.Str::uuid()"
                />

            </div>
        </div>
        @else
        <p class="text-white text-sm" wire:loading.remove wire:target="seleccionarPersona">
            Haz clic en una persona para ver sus datos.
        </p>
        @endif
    </div>
</main>
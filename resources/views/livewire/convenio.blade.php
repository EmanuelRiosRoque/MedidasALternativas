<div class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft"></div>

    <div class="relative mt-2 mb-2 z-10 w-full max-w-5xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">

        <!-- Navbar como Tab Bar -->
        <flux:navbar class="mb-6 justify-center">
            <flux:navbar.item 
                wire:click.prevent="cambiarTab(1)" 
                icon="document" 
                :current="$tab === 1"
            >
                Datos generales
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="cambiarTab(2)" 
                icon="hand-raised" 
                :current="$tab === 2"
            >
                Solicitante
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="cambiarTab(3)" 
                icon="users" 
                :current="$tab === 3"
            >
                Invitado
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="cambiarTab(4)" 
                icon="paper-clip" 
                :current="$tab === 4"
            >
                Documentos
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="cambiarTab(5)" 
                icon="bookmark-square" 
                :current="$tab === 5"
            >
                Finalizar
            </flux:navbar.item>
        </flux:navbar>

        <!-- Skeleton Loader cuando se está cambiando de tab -->
        <div wire:loading wire:target='cambiarTab' class="mb-6 w-full mx-auto">
            @include('components.convenio.includes.skeleton-loader')
        </div>
        
        

        <!-- Contenido del tab -->
        <div class="p-4 rounded-md dark:bg-neutral-900" wire:loading.remove wire:target='cambiarTab'>
            @if ($tab === 1)
                @include('convenio.datosGenerales')
            @elseif ($tab === 2)
                @include('convenio.datosSolicitante')
            @elseif ($tab === 3)
                @include('convenio.datosInvitado')
            @elseif ($tab === 4)
                @include('convenio.combosDocumentos')
            @elseif ($tab === 5)
                {{-- <div class="flex justify-end">
                    <button 
                        wire:click="save"
                        type="button"
                        class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 mt-5 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
                    >
                        Guardar Registro
                    </button>
                </div> --}}
            @endif
               
                
            
            
        </div>

        <div class="mt-10 pt-6 border-t border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
            @if ($tab > 1)
                <button
                    wire:click="cambiarTab({{ $tab - 1 }})"
                    type="button"
                    class="flex items-center gap-2 bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-white hover:bg-neutral-200 dark:hover:bg-neutral-700 font-medium px-5 py-2.5 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Anterior
                </button>
            @else
                <div></div>
            @endif
        
            @if ($tab < 5)
                <button
                    wire:click="cambiarTab({{ $tab + 1 }})" 
                    type="button"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg"
                >
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @endif
            
            {{-- <button
                    wire:click="guardado" 
                    type="button"
                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-300 hover:shadow-lg"
                >
                    Guardar
                </button> --}}
        </div>
    </div>
</div>

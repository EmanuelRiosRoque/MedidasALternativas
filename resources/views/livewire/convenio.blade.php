<div class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft"></div>

    <div class="relative mt-2 mb-2 z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">

        <!-- Navbar como Tab Bar -->
        <flux:navbar class="mb-6 justify-center">
            <flux:navbar.item 
                wire:click.prevent="$set('tab', 1)" 
                icon="document" 
                :current="$tab === 1"
            >
                Datos generales
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="$set('tab', 2)" 
                icon="hand-raised" 
                :current="$tab === 2"
            >
                Solicitante
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="$set('tab', 3)" 
                icon="users" 
                :current="$tab === 3"
            >
                Invitado
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="$set('tab', 4)" 
                icon="paper-clip" 
                :current="$tab === 4"
            >
                Documentos
            </flux:navbar.item>

            <flux:navbar.item 
                wire:click.prevent="$set('tab', 5)" 
                icon="bookmark-square" 
                :current="$tab === 5"
            >
                Finalizar
            </flux:navbar.item>
        </flux:navbar>

        <!-- Skeleton Loader cuando se está cambiando de tab -->
        <div wire:loading wire:target='tab' class="mb-6 w-full mx-auto">
            <div class="relative animate-pulse p-6 bg-white dark:bg-neutral-800 rounded-xl shadow-md space-y-6">
                
                {{-- Campos tipo formulario --}}
                <div class="space-y-4">
                    <div>
                        {{-- Etiqueta simulada --}}
                        <div class="h-4 w-1/3 bg-neutral-300 dark:bg-neutral-600 rounded mb-2"></div>
                
                        {{-- Área Dropzone simulada con ícono y texto --}}
                        <div class="h-24 w-full bg-neutral-300 dark:bg-neutral-600 rounded-lg flex flex-col items-center justify-center gap-3">
                            {{-- Ícono de archivo simulado (cuadro con una esquina doblada) --}}
                            <svg class="w-10 h-10 text-gray-200 dark:text-neutral-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
                                <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
                            </svg>
                
                            {{-- Texto falso simulado --}}
                            <div class="h-4 w-24 bg-neutral-400 dark:bg-neutral-500 rounded"></div>
                        </div>
                    </div>
                </div>
                
        
                {{-- Radios tipo formulario --}}
                <div>
                    <div class="h-4 w-1/4 bg-neutral-300 dark:bg-neutral-600 rounded mb-3"></div>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-3">
                            <div class="h-5 w-5 rounded-full bg-neutral-300 dark:bg-neutral-600"></div>
                            <div class="h-4 w-32 bg-neutral-300 dark:bg-neutral-600 rounded-lg"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-5 w-5 rounded-full bg-neutral-300 dark:bg-neutral-600"></div>
                            <div class="h-4 w-24 bg-neutral-300 dark:bg-neutral-600 rounded-lg"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-5 w-5 rounded-full bg-neutral-300 dark:bg-neutral-600"></div>
                            <div class="h-4 w-40 bg-neutral-300 dark:bg-neutral-600 rounded-lg"></div>
                        </div>
                    </div>
                </div>
        
                {{-- Botones de acción simulados --}}
                <div class="flex justify-end gap-4 pt-4">
                    <div class="h-10 w-20 bg-neutral-300 dark:bg-neutral-600 rounded-lg"></div>
                </div>
            </div>
        </div>
        
        

        <!-- Contenido del tab -->
        <div class="p-4 rounded-md dark:bg-neutral-900" wire:loading.remove wire:target='tab'>
            @if ($tab === 1)
                @include('convenio.datosGenerales')
            @elseif ($tab === 2)
                @include('convenio.datosSolicitante')
            @elseif ($tab === 3)
                @include('convenio.datosInvitado')
            @elseif ($tab === 4)
                @include('convenio.combosDocumentos')
            @elseif ($tab === 5)
                <div class="flex justify-end">
                    <button 
                        wire:click="save"
                        type="button"
                        class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-3 mt-5 py-2 rounded-lg shadow-lg hover:scale-105 transition-all duration-300"
                    >
                        Guardar Registro
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

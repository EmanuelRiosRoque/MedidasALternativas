<div class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn"></div>

    <div class="relative mt-2 mb-2 z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeIn">

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


        
        <!-- Contenido del tab -->
        <div class="p-4 rounded-md  dark:bg-neutral-900 ">
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

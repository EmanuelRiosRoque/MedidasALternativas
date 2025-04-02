<div class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] bg-teal-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn"></div>

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
        </flux:navbar>



        
        <!-- Contenido del tab -->
        <div class="p-4 rounded-md  dark:bg-neutral-900 shadow">
            @if ($tab === 1)
                @include('convenio.datosGenerales')
            @elseif ($tab === 2)
                @include('convenio.datosSolicitante')
            @elseif ($tab === 3)
                @include('convenio.datosInvitado')
            @endif
        </div>
    </div>
</div>

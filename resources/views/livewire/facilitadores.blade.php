<div class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-gray-100 dark:from-gray-800 dark:to-gray-900 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 right-1/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn"></div>

    <div class="relative mt-2 mb-2 z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 bg-white dark:bg-gray-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeIn">

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
                icon="document" 
                :current="$tab === 2"
            >
                Datos adicionales
            </flux:navbar.item>
        </flux:navbar>

        <!-- Contenido del tab -->
        <div class="p-4 rounded-md  dark:bg-gray-900 ">
            @if ($tab === 1)
                <flux:radio.group wire:model.live="tipo_facilitador" label="Faclitador">
                    <flux:radio value="publico" label="Público" />
                    <flux:radio value="privado" label="Privado" />
                </flux:radio.group>
                @if ($tipo_facilitador != '')
                @include('facilitadores.formulario.datosGenerales')
                @endif
            @elseif ($tab === 2)
            @if ($tipo_facilitador === "publico")
                @include('facilitadores.formulario.publico')
            @elseif($tipo_facilitador === "privado")
                @include('facilitadores.formulario.privado')
            @endif
            @elseif ($tab === 3)

            @endif

        </div>
    </div>
</div>

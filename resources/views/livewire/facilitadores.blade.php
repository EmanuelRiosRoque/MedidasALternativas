<div
    class="relative h-full place-items-center px-4 dark:from-gray-800 dark:to-gray-900 overflow-hidden">

    <div class="relative mt-2 mb-2 z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 bg-white dark:bg-gray-900 p-6 sm:p-8 lg:p-10">

        <flux:navbar class="mb-6 justify-center">
            <flux:navbar.item wire:click="cambiarTab(1)" icon="document" :current="$tab === 1">
                Datos generales
            </flux:navbar.item>

            <flux:navbar.item wire:click="cambiarTab(2)" icon="folder" :current="$tab === 2">
                Datos adicionales
            </flux:navbar.item>
        </flux:navbar>

        <div wire:loading wire:target='cambiarTab' class="mb-6 w-full mx-auto">
            @include('components.convenio.includes.skeleton-loader')
        </div>

        <div class="p-4 rounded-md  dark:bg-gray-900 " 
            x-data="{tipo: @entangle('tipo').live}"
            wire:loading.remove wire:target='cambiarTab'>
            @if ($tab === 1)
                @include('facilitadores.formulario.datosGenerales')
            @elseif ($tab === 2)
                @include('facilitadores.formulario.datosAdicionales')
            @endif
        </div>

        <div class="mt-10 pt-6 border-t border-neutral-200 dark:border-neutral-700 flex justify-between items-center">
            @if ($tab > 1)
                <flux:button wire:click="cambiarTab({{ $tab - 1 }})" icon="arrow-left">
                    Anterior
                </flux:button>
            @else
                <div></div>
            @endif
        
            @if ($tab < 2)
                <flux:button wire:click="cambiarTab({{ $tab + 1 }})" icon:trailing="arrow-right" variant='primary'>
                    Siguiente
                </flux:button>
            @endif
   
            @if ($tab === 2)
            <flux:button wire:click="save"  variant='primary'>
                Guardar
            </flux:button>
            @endif
        </div>
    </div>
</div>
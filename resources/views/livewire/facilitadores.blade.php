<div
    class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-gray-100 dark:from-gray-800 dark:to-gray-900 overflow-hidden">
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 right-1/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn">
    </div>

    <div
        class="relative mt-2 mb-2 z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 bg-white dark:bg-gray-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeIn">

        <!-- Navbar como Tab Bar -->
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

        <!-- Contenido del tab -->
        <div class="p-4 rounded-md  dark:bg-gray-900 " 
            x-data="{tipo: @entangle('tipo').live}"
            wire:loading.remove wire:target='cambiarTab'    
        >
            @if ($tab === 1)
            <div class=" grid grid-cols-4 gap-4 mb-4" >
                <flux:radio.group wire:model="tipo" label="Persona facilitadora">
                    <flux:radio value="1" label="Público" />
                    <flux:radio value="2" label="Privado" />
                </flux:radio.group>

                <flux:radio.group wire:model="materia" label="Materias Certificado">
                    <flux:radio value="1" label="Civil-Mercantil " />
                    <flux:radio value="2" label="Familiar" />
                    <flux:radio value="3" label="Ambas" />
                </flux:radio.group>

                <flux:radio.group wire:model="estudios" label="Grado de estudios">
                    <flux:radio value="1" label="Licenciatura" />
                    <flux:radio value="2" label="Maestria" />
                    <flux:radio value="3" label="Doctorado" />
                </flux:radio.group>

                <flux:input
                    wire:model="cedula"
                    :label="__('Número de Cédula Profesional')"
                    type="text"
                    required
                    placeholder="Número de Cédula Profesional, expedida por la Dirección General de Profesiones"
                />
            </div>

                <div x-show="tipo !== ''" x-cloak class="mt-4">
                    @include('facilitadores.formulario.datosGenerales')
                </div>
            @elseif ($tab === 2)
        
                @include('facilitadores.formulario.publico')
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
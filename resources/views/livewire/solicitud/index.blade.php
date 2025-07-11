<div
    class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft">
    </div>

    <div
        class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">
        <x-solicitud.section-header title="Detalles de la Solicitud">
            Revisa los datos capturados, asigna facilitador y consulta a los participantes.
        </x-solicitud.section-header>

        {{-- Lista de personas --}}
        @include('livewire.solicitud.includes.listas-personas')

        @if ($solicitud->modalidad != 'presencial')
        <x-solicitud.section-header title="Pre-Mediacion">
            Recuerda que es una sesión vía Google Meet. Puedes generarla aquí:
            <a class="text-emerald-600" href="https://meet.google.com/landing" target="_blank"
                rel="noopener noreferrer">
                "Ir a generar sesión"
            </a>
        </x-solicitud.section-header>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-6xl m-auto" x-data="{ medio_envio: 'correo' }">
            <!-- Callout de recordatorio -->
            @include('livewire.solicitud.includes.recordatorio-callout')

            <!-- Sección de selección -->
            <div>
                <flux:radio.group label="Medio de envío" x-model="medio_envio">
                    <flux:radio value="correo" label="Correo" />
                    <flux:radio value="sepomex" label="SEPOMEX" />
                    <flux:radio value="personal" label="Personal" />
                </flux:radio.group>
            </div>

            <!-- Contenido dinámico según la opción -->
            <div x-show="medio_envio === 'correo'" x-cloak>
                <livewire:solicitud.invitacion-evento :evento="$evento" />
            </div>

            <div x-show="medio_envio === 'personal'" x-cloak>
                <flux:button variant="primary" icon="arrow-down-tray" >Entrega personal</flux:button>
            </div>

            <div x-show="medio_envio === 'sepomex'" x-cloak>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('descargar-amparo') }}">
                        <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                            Amaparo
                        </flux:button>
                    </a>
                
                    <a href="{{ route('descargar-correoMexico') }}">
                        <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                            Correos México
                        </flux:button>
                    </a>

                    <a href="{{ route('descargar-servicioPostal') }}">
                        <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                            Servicio Postal
                        </flux:button>
                    </a>                                    
                </div>
            </div>
        </div>


        @else
        <div class=" max-w-md m-auto space-y-3 mt-5">
            <!-- Callout de recordatorio -->
            @include('livewire.solicitud.includes.recordatorio-callout')
        </div>
        @endif
    </div>
</div>
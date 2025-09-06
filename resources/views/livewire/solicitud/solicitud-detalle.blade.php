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

        @if ($solicitud->materia != 'familiar')
        <div
            class="flex pl-8 flex-col items-start text-sm text-neutral-600 dark:text-neutral-300 font-semibold space-y-1">
            <span>Materia:</span>

            <flux:radio.group wire:model.change="materia">
                <flux:radio value="civil" label="Civil" />
                <flux:radio value="mercantil" label="Mercantil" />
            </flux:radio.group>
        </div>
        @endif

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-6xl m-auto">

            <!-- Columna izquierda: callouts -->
            <div class="space-y-5">
                @include('livewire.solicitud.includes.recordatorio-callout')
            </div>
            <div>
                @if($invitacion2)
                @include('livewire.solicitud.includes.segunda-invitacion-callout')
                @endif
            </div>
        </div>
        @else
        <div class="max-w-6xl m-auto space-y-3 mt-5 grid grid-cols-3 gap-4" x-data="{ medio_envio: 'sepomex' }">
            <div>
                @include('livewire.solicitud.includes.recordatorio-callout')
            </div>
            @if($segSesion != null)
            @include('livewire.solicitud.includes.segunda-invitacion-callout')
            @endif

            {{-- Opciones Presencial --}}
            <div>
                <flux:radio.group label="Medio de envío" x-model="medio_envio">
                    <flux:radio value="sepomex" label="SEPOMEX" />
                    <flux:radio value="personal" label="Personal" />
                </flux:radio.group>
            </div>

            {{-- Documentos --}}
            <div>
                <div x-show="medio_envio === 'personal'" x-cloak>
                    <div class="flex flex-col space-y-2">

                        <a href="{{ route('descargar-inv1', [$solicitudId]) }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Invitación para invitación 1
                            </flux:button>
                        </a>

                        <a href="{{ route('descargar-inv2', [$solicitudId]) }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Entrega personal invitación 2
                            </flux:button>
                        </a>

                        <a href="{{ route('sobre-personal', [$solicitudId]) }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Descargar Sobre personal
                            </flux:button>
                        </a>
                    </div>
                </div>

                <div x-show="medio_envio === 'sepomex'" x-cloak>
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('descargar-amparo') }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Amparo
                            </flux:button>
                        </a>

                        <a href="{{ route('descargar-amparoRepre') }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Amparo Representante
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

                        <a href="{{ route('descargar-segui',  ['fecha' => $evento->fecha ?? date('Y-m-d')]) }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Seguimiento
                            </flux:button>
                        </a>

                        <a href="{{ route('sobre-sepomex', [$solicitudId]) }}">
                            <flux:button class="w-full" variant="primary" icon="arrow-down-tray">
                                Descargar Sobre
                            </flux:button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif



        <x-solicitud.section-header title="Registro de invitaciones">
           Aqui puedes ver las invitaciones generadas
        </x-solicitud.section-header>
       <livewire:solicitud.invitaciones-panel
            :evento="$evento"
            :solicitudId="$solicitud->id"
            :modalidad="$solicitud->modalidad"
        />

    </div>
</div>
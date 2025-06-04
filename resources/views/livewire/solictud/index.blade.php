<div
    class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft">
    </div>

    <div class="relative mt-2 mb-2 z-10 w-full max-w-8xl rounded-2xl shadow-2xl ring-1 ring-neutral-200 dark:ring-neutral-700 bg-white dark:bg-neutral-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeInUp">

       <div class="text-center border-b border-emerald-200 dark:border-emerald-700 pb-6 mb-6 max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">
                Detalles de la Solicitud
            </h1>
            <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                Revisa los datos capturados, asigna facilitador y consulta a los participantes.
            </p>
        </div>

        {{-- Lista de personas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 md:gap-1 gap-2">
            <x-lista-personas :personas="$solicitantes" titulo="Lista Solicitantes" />

            <div class="mx-auto right-0  mt-9 w-60">
                <div class="shadow-lg">
                    <div class="text-center p-4 w-full max-w-md bg-white rounded-lg shadow-md sm:p-8 dark:bg-neutral-800 dark:border-neutral-700 border hover:border-emerald-600 transform transition duration-300 ease-in-out hover:scale-[1.02] ">
                        <svg aria-hidden="true" role="img" class="h-24 w-24 text-white rounded-full mx-auto" width="32" height="32"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                            <path fill="currentColor"
                                d="M172 120a44 44 0 1 1-44-44a44 44 0 0 1 44 44Zm60 8A104 104 0 1 1 128 24a104.2 104.2 0 0 1 104 104Zm-16 0a88 88 0 1 0-153.8 58.4a81.3 81.3 0 0 1 24.5-23a59.7 59.7 0 0 0 82.6 0a81.3 81.3 0 0 1 24.5 23A87.6 87.6 0 0 0 216 128Z">
                            </path>
                        </svg>
                        <p class="pt-2 text-lg font-semibold text-neutral-50">{{ $solicitud->facilitador->nombre ?? 'Sin asignar' }}</p>
                        <p class="pt-2 text-sm font-semibold text-neutral-100">Facilitador</p>
                    </div>
                </div>
            </div>

            <x-lista-personas :personas="$invitados" titulo="Lista Invitados" />
        </div>

        
        @if ($solicitud->modalidad != 'presencial')
           <div class="text-center border-b border-emerald-200 dark:border-emerald-700 pb-6 mb-6 mt-20 max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">
                    Pre-Mediacion
                </h1>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    Agrega tu liga de sesión virtual, el dia y la hora de la sesión
                </p>
                <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                    Recuerda que es una sesión vía Google Meet. Puedes generarla aquí:
                    <a class="text-emerald-600" href="https://meet.google.com/landing" target="_blank" rel="noopener noreferrer">
                        "Ir a generar sesión"
                    </a>
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl	m-auto ">
                <!-- Callout de recordatorio -->
                <flux:callout variant="sparkles" icon="bell">
                    <flux:callout.heading>
                        Recordatorio 
                        <flux:badge color="purple" size="sm" inset="top bottom">De asignación</flux:badge>
                    </flux:callout.heading>

                    <flux:callout.text>
                        <p class="text-sm font-semibold text-neutral-200 mb-2">¿A quién se te asignó darle sesión?</p>
                        <ul class="list-disc list-inside text-sm text-neutral-100 space-y-1">
                            <li>
                                Para esta pre mediación se asignó a:
                                <span class="text-emerald-600 font-semibold">{{ $evento->opcion_invitacion }}</span>.
                            </li>
                            <li>
                                Con fecha de atención:
                                <span class="text-emerald-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                                </span>.
                            </li>
                            <li>
                                Horario asignado:
                                <span class="text-emerald-600 font-semibold">
                                    {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('g:i a') }} a
                                    {{ \Carbon\Carbon::parse($evento->hora_fin)->format('g:i a') }}
                                </span>.
                            </li>
                        </ul>
                    </flux:callout.text>
                </flux:callout>

                <!-- Sección de enlace -->
                <div class="flex flex-col justify-between space-y-4">
                    <flux:input label="Enlace / Liga" placeholder="Enlace de reunión virtual" />
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">Enviar invitación</flux:button>
                    </div>
                </div>
            </div>

        @else
        
        <div class=" max-w-md m-auto space-y-3 mt-5">
            <flux:callout variant="sparkles" icon="bell">
                <flux:callout.heading>
                    Recordatorio <flux:badge color="purple" size="sm" inset="top bottom">De asignación</flux:badge>
                </flux:callout.heading>
    
                <flux:callout.text>
                    <p class="text-sm font-semibold text-neutral-200 mb-2">¿A quién se te asignó darle sesión?</p>
                    <ul class="list-disc list-inside text-sm text-neutral-100 space-y-1">
                        <li>
                            Para esta pre mediación se asignó a: 
                            <span class="text-emerald-600 font-semibold">{{ $evento->opcion_invitacion }}</span>.
                        </li>
                        <li>
                            Con fecha de atención: 
                            <span class="text-emerald-600 font-semibold">
                                {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}
                            </span>.
                        </li>
                        <li>
                            Horario asignado: 
                            <span class="text-emerald-600 font-semibold">
                                {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('g:i a') }} a 
                                {{ \Carbon\Carbon::parse($evento->hora_fin)->format('g:i a') }}
                            </span>.
                        </li>
                    </ul>
                </flux:callout.text>
    
                {{-- <x-slot name="actions">
                    <flux:button>Ver detalles</flux:button>
                    <flux:button variant="ghost" class="@max-md:hidden">Administrar solicitud</flux:button>
                </x-slot> --}}
            </flux:callout>
        </div>
        @endif
        
    </div>


    <flux:modal wire:model="mostrarModal" class="md:w-[70rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar usuario</flux:heading>
            </div>
            <div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <flux:input wire:model='nombre' label="Nombre" placeholder="Nombre" />
                    <flux:input wire:model='apellido_p' label="Apellido paterno" placeholder="Apellido paterno" />
                    <flux:input wire:model='apellido_m' label="Apellido materno" placeholder="Apellido materno" />

                    <flux:input wire:model='rfc' label="FRC" placeholder="Rfc" />
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                            Sexo
                        </label>
                        <flux:select wire:model="sexo" placeholder="Elige sexo...">
                            <flux:select.option>Femenino</flux:select.option>
                            <flux:select.option>Masculino</flux:select.option>
                        </flux:select>
                    </div>
                    <flux:input wire:model='edad' label="Edad" placeholder="Edad" />
                    <flux:input wire:model='fecha_nacimiento' label="Fecha de nacimiento" type="date" />

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                            Escolaridad
                        </label>
                        <flux:select wire:model="escolaridad" placeholder="Elige escolaridad...">
                            @foreach ($escolaridades as $escolaridad)
                            <flux:select.option>{{ $escolaridad }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200 mb-1">
                            Ocupación
                        </label>
                    
                        <flux:select wire:model="ocupacion" placeholder="Elige Ocupació...">
                            @foreach ($ocupaciones as $ocupacion)
                            <flux:select.option>{{ $ocupacion }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        @foreach ($correos as $correo)
                            <p>{{ $correo }}</p>
                        @endforeach
                    </div>


                </div>

            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
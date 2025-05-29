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
            <x-lista-personas :personas="$invitados" titulo="Lista Invitados" />

            <div class="mx-auto right-0 mt-2 w-60">
                <div class="bg-white rounded overflow-hidden shadow-lg">
                    <div class="text-center p-6 bg-neutral-800 border-b">
                        <svg aria-hidden="true" role="img" class="h-24 w-24 text-white rounded-full mx-auto" width="32" height="32"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                            <path fill="currentColor"
                                d="M172 120a44 44 0 1 1-44-44a44 44 0 0 1 44 44Zm60 8A104 104 0 1 1 128 24a104.2 104.2 0 0 1 104 104Zm-16 0a88 88 0 1 0-153.8 58.4a81.3 81.3 0 0 1 24.5-23a59.7 59.7 0 0 0 82.6 0a81.3 81.3 0 0 1 24.5 23A87.6 87.6 0 0 0 216 128Z">
                            </path>
                        </svg>
                        <p class="pt-2 text-lg font-semibold text-neutral-50">S/N</p>
                        <p class="text-sm text-neutral-100">Facilitador sin asginar</p>
                        <div class="mt-5">
                            <a
                                class="border cursor-pointer hover:bg-emerald-700  rounded-full py-2 px-4 text-xs font-semibold text-neutral-100">
                                Asignar Facilitador
                            </a>
                        </div>
                    </div>
                    <div class="border-b">
                        <Link href="/account/campaigns">
                        <a class="px-4 py-2 hover:bg-neutral-100 flex">
                            <div class="text-green-600">
                                <svg fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1"
                                    viewBox="0 0 24 24" class="w-5 h-5">
                                    <path
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <div class="pl-3">
                                <p class="text-sm font-medium text-neutral-800 leading-none">
                                    Infracciones
                                </p>
                                <p class="text-xs text-neutral-500">0</p>
                            </div>
                        </a>
                        </Link>
                        <Link href="/account/donations">
                        <a class="px-4 py-2 hover:bg-neutral-100 flex">
                            <div class="text-neutral-800">
                                <svg fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="1"
                                    viewBox="0 0 24 24" class="w-5 h-5">
                                    <path
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="pl-3">
                                <p class="text-sm font-medium text-neutral-800 leading-none">Mediaciones</p>
                                <p class="text-xs text-neutral-500">0</p>
                            </div>
                        </a>
                        </Link>
                    </div>
            
                    <div class="">
                        <a href="#" class="w-full px-4 py-2 pb-4 hover:bg-neutral-100 flex cursor-pointer">
                            <p class="text-sm font-medium text-neutral-800 leading-none">
                                Ver perfil
                            </p>
                        </a>
                    </div>
                </div>
            </div>
            
            <x-lista-personas :personas="$solicitantes" titulo="Lista Solicitantes" />
        </div>


        <div class="text-center border-b border-emerald-200 dark:border-emerald-700 pb-6 mb-6 mt-6 max-w-4xl mx-auto">
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
        {{-- Documentos de la solicitud --}}
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
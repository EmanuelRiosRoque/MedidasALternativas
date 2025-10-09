<div class="relative inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
    <!-- Componente principal -->
    <div class="h-[85vh] flex items-center justify-center overflow-visible z-10 relative px-4">
        <section class="w-full max-w-7xl z-20 animate__animated animate__fadeInUp">
            <!-- Encabezado -->
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-lg font-medium text-neutral-800 dark:text-white">Solicitudes</h2>
                        <span
                            class="px-3 py-1 text-xs text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/30 dark:text-emerald-300">
                            {{ $numSolicitudes }} {{ $numSolicitudes == 1 ? 'Solicitud' : 'Solicitudes' }}
                        </span>
                    </div>
                    <p
                        class="mt-1 text-sm text-neutral-500 dark:text-neutral-300 hover:text-emerald-700 transition-all cursor-default">
                        Cantidad total de solicitudes registradas.
                    </p>
                </div>

                {{-- <div class="flex items-center mt-4 gap-x-3">
                    <a wire:navigate href={{ route('convenio.index') }}
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-emerald-700 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-emerald-800 dark:hover:bg-emerald-00 dark:bg-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Nueva Solicitud</span>
                    </a>
                </div> --}}
            </div>

            <!-- Buscador -->
            <div class="mt-6 md:flex md:items-center md:justify-between">
                <div class="relative flex items-center mt-4 md:mt-0">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mx-3 text-neutral-400 dark:text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" placeholder="Buscar por Folio o #Ticket" wire:model.live="search"
                        class="block w-full py-1.5 pr-5 bg-white border border-neutral-200 rounded-lg md:w-80 placeholder-neutral-400/70 pl-11 dark:bg-neutral-900 text-emerald-700 dark:text-neutral-300 dark:border-neutral-600 focus:border-emerald-400 dark:focus:border-emerald-300 focus:ring-emerald-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <!-- Tabla -->
            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-visible relative sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-visible border border-neutral-200 dark:border-neutral-700 md:rounded-lg">
                            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                                <thead class="bg-neutral-100 dark:bg-neutral-800">
                                    <tr>
                                        <th
                                            class="py-3.5 px-4 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Folio</th>
                                        <th
                                            class="px-12 py-3.5 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Estatus</th>
                                        <th
                                            class="px-12 py-3.5 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Proceso</th>
                                        <th
                                            class="px-12 py-3.5 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Modalidad</th>
                                        <th
                                            class="px-12 py-3.5 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Acudirán Juntos</th>
                                        <th
                                            class="px-4 py-3.5 text-sm font-normal text-left text-neutral-500 dark:text-neutral-400">
                                            Fecha y hora</th>
                                        <th
                                            class="py-3.5 px-4 relative text-sm font-normal text-neutral-500 dark:text-neutral-400">
                                            Opciones</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-neutral-50 divide-y divide-neutral-200 dark:divide-neutral-700 dark:bg-neutral-900">
                                    @forelse ($solicitudes as $solicitud)
                                    <tr>
                                        <td
                                            class="px-4 py-4 text-sm font-medium whitespace-nowrap text-neutral-800 dark:text-white">
                                            {{ $solicitud->folio_materia }}

                                            @if ($solicitud->numero_ticket)
                                            <div class="text-xs text-neutral-500">
                                                #{{ $solicitud->numero_ticket }}
                                            </div>
                                            @endif
                                        </td>
                                        <td class="px-12 py-4 text-sm whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 bg-emerald-100/60 dark:bg-neutral-800">
                                                {{ $solicitud->estatus->nombre ?? 'Sin estatus' }}
                                            </div>
                                        </td>

                                        <td class="px-12 py-4 text-sm whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 bg-emerald-100/60 dark:bg-neutral-800">
                                                {{ $solicitud->proceso->nombre ?? 'Sin proceso' }}
                                            </div>
                                        </td>

                                        <td class="px-12 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-normal
                                                {{ $solicitud->modalidad == 2
                                                    ? 'text-sky-600 bg-sky-100/70'
                                                    : 'text-emerald-600 bg-emerald-100/70' }}
                                                dark:bg-neutral-800">
                                                {{ $solicitud->modalidad == 2 ? 'En línea' : 'Presencial' }}
                                            </span>
                                        </td>

                                        <td class="px-12 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-normal
                                                {{ (int)$solicitud->acudiran_juntos === 1
                                                    ? 'text-emerald-600 bg-emerald-100/70'
                                                    : 'text-rose-600 bg-rose-100/70' }}
                                                dark:bg-neutral-800">
                                                {{ (int)$solicitud->acudiran_juntos === 1 ? 'Sí' : 'No' }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">
                                                    {{
                                                    $solicitud->created_at->timezone('America/Mexico_City')->format('d/m/y')
                                                    }}
                                                </h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">
                                                    {{
                                                    $solicitud->created_at->timezone('America/Mexico_City')->format('h:i
                                                    A')
                                                    }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap" x-data="{ open: false }"
                                            @click.away="open = false">
                                            <div class="relative inline-block text-left">
                                                <button @click="open = !open"
                                                    class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                    </svg>
                                                </button>
                                                <div x-show="open" x-transition
                                                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-md shadow-lg z-50"
                                                    style="display: none;">
                                                    <ul class="text-sm text-gray-700 dark:text-gray-200">
                                                        <li>
                                                            <a href="{{ route('solicitud.show', $solicitud->id) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Ver
                                                            </a>
                                                        </li>
                                                        @if ($solicitud->estatus_id == "7")
                                                        <li>
                                                            <a href="{{ route('solicitud.show', $solicitud->id) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Ver
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('calendar.reasignacion', ['solicitudID' => $solicitud->id]) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Reasignar
                                                            </a>
                                                        </li>
                                                        @else
                                                        @if ($solicitud->tipo_proceso_id == 2)
                                                        @if ($solicitud->facilitador_id)
                                                        <li>
                                                            <a href="{{ route('solicitud.show', $solicitud->id) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Ver
                                                            </a>
                                                        </li>
                                                        @else
                                                        <li>
                                                            <a href="{{ route('calendar.index') }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Asignar
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @else
                                                        @if ($solicitud->estatus_id != "1")
                                                        <li>
                                                            <a href="{{ route('solicitud.show', $solicitud->id) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Ver
                                                            </a>
                                                        </li>
                                                        @endif

                                                        @if ($solicitud->estatus_id == "2")
                                                        <li>
                                                            <a href="{{ route('calendar.reasignacion', ['solicitudID' => $solicitud->id]) }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Reasignar
                                                            </a>
                                                        </li>
                                                        @else
                                                        <li>
                                                            <a href="{{ route('calendar.index') }}"
                                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700">
                                                                Asignar
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @endif
                                                        @endif

                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-4 py-4 text-sm text-center text-neutral-500 dark:text-neutral-300">
                                            No hay solicitudes aún.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <div
                                class="px-4 py-3 bg-white dark:bg-neutral-900 border-t border-neutral-200 dark:border-neutral-700">
                                {{ $solicitudes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
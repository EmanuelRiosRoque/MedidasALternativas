<div class="relative inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 rigth-1/2 -translate-x-1/2 -translate-y-1/2 z-0 pointer-events-none animate__animated animate__backInRight">
    </div>

    <!-- component -->
    <div class="h-[85vh] flex items-center justify-center overflow-hidden z-10 relative px-4">
        <section class="w-full max-w-7xl z-20 animate__animated animate__fadeInUp">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-lg font-medium text-neutral-800 dark:text-white">Convenios</h2>

                        <span
                            class="px-3 py-1 text-xs text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/30 dark:text-emerald-300">240
                            Convenios</span>
                    </div>

                    <p
                        class="mt-1 text-sm text-neutral-500 dark:text-neutral-300 hover:text-emerald-700 transition-all cursor-default">
                        Cantidad total de convenios registrados.</p>
                </div>

                <div class="flex items-center mt-4 gap-x-3">

                    <a wire:navigate href={{ route('convenio.index') }}
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-emerald-700 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-emerald-800 dark:hover:bg-emerald-00 dark:bg-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <span>Nuevo Convenio</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 md:flex md:items-center md:justify-between">
                {{-- <div
                    class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-neutral-900 rtl:flex-row-reverse dark:border-neutral-700 dark:divide-neutral-700">
                    <button
                        class="px-5 py-2 text-xs font-medium text-neutral-600 transition-colors duration-200 bg-neutral-100 sm:text-sm dark:bg-neutral-800 dark:text-neutral-300 hover:text-emerald-700 cursor-pointer">
                        Ver todo
                    </button>

                    <button
                        class="px-5 py-2 text-xs font-medium text-neutral-600 transition-colors duration-200 sm:text-sm dark:hover:bg-neutral-800 dark:text-neutral-300 hover:text-emerald-700 cursor-pointer ">

                    </button>
                </div> --}}

                <div class="relative flex items-center mt-4 md:mt-0">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mx-3 text-neutral-400 dark:text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>

                    <input type="text" placeholder="Buscar por ID o #Ticket"
                        class="block w-full py-1.5 pr-5 bg-white border border-neutral-200 rounded-lg md:w-80 placeholder-neutral-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-neutral-900 text-emerald-700 dark:text-neutral-300 hover:text-white cursor-pointer dark:border-neutral-600 focus:border-emerald-400 dark:focus:border-emerald-300 focus:ring-emerald-300 focus:outline-none focus:ring focus:ring-opacity-40">
                </div>
            </div>

            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-neutral-200 dark:border-neutral-700 md:rounded-lg">
                            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                                <thead class="bg-neutral-50 dark:bg-neutral-800">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            <button class="flex items-center gap-x-3 focus:outline-none">
                                                <span>ID / Ticket</span>
                                            </button>
                                        </th>

                                        <th scope="col"
                                            class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            Estatus
                                        </th>

                                        <th scope="col"
                                            class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            Fecha y hora
                                        </th>

                                        <th scope="col" class="relative py-3.5 px-4">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-neutral-200 dark:divide-neutral-700 dark:bg-neutral-900">
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">44557</h2>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                                Registrado
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">02/04/25</h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">10:40 Am</p>
                                            </div>
                                        </td>


                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button
                                                class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">88557</h2>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal text-neutral-500 bg-neutral-100 rounded-full dark:text-neutral-400 gap-x-2 dark:bg-neutral-800">
                                                Registrado
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">02/04/25</h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">10:40 Am</p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button
                                                class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">33557</h2>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                                Registrado
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">02/04/25</h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">10:40 Am</p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button
                                                class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">99557</h2>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal text-neutral-500 bg-neutral-100 rounded-full dark:text-neutral-400 gap-x-2 dark:bg-neutral-800">
                                                Registrado
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">02/04/25</h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">10:40 Am</p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button
                                                class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">55557</h2>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                                Registrado
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div>
                                                <h4 class="text-neutral-700 dark:text-neutral-200">02/04/25</h4>
                                                <p class="text-neutral-500 dark:text-neutral-400">10:40 Am</p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <button
                                                class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:text-emerald-700 cursor-pointer ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:flex sm:items-center sm:justify-between ">
                <div class="text-sm text-neutral-500 dark:text-neutral-400">
                    Page <span class="font-medium text-neutral-700 dark:text-neutral-100">1 of 10</span>
                </div>

                <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
                    <a href="#"
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2  dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>

                        <span>
                            Anterior
                        </span>
                    </a>

                    <a href="#"
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2  dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                        <span>
                            Siguiente
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
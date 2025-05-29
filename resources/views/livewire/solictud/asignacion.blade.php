<div
    class="relative h-full place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">
    <div
        class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-2/2 left-2/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceInLeft">
    </div>

        <section class="container p-8 mx-auto">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-x-3">
                        <h2 class="text-lg font-medium text-neutral-800 dark:text-white">Asignaciones</h2>
                        <span class="px-3 py-1 text-xs text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/30 dark:text-emerald-300">4 Disponibles</span>
                    </div>
    
                    <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-300">
                        Estos son los facilitadores disponibles
                    </p>
                </div>
            </div>
    
            <div class="mt-6 md:flex md:items-center md:justify-between">
               
    
                <div class="relative flex items-center mt-4 md:mt-0">
                    <span class="absolute">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mx-3 text-neutral-400 dark:text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
    
                    <input type="text" placeholder="Search"
                        class="block w-full py-1.5 pr-5 text-neutral-700 bg-white border border-neutral-200 rounded-lg md:w-80 placeholder-neutral-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-600 focus:border-emerald-400 dark:focus:border-emerald-300 focus:ring-emerald-300 focus:outline-none focus:ring focus:ring-opacity-40">
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
                                            Nombre Facilitador           
                                        </th>
    
                                        <th scope="col"
                                            class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            Estatus
                                        </th>                                        
    
                                        <th scope="col"
                                            class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            En curso
                                        </th>
    
                                        <th scope="col"
                                            class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                            License use</th>
    
                                        <th scope="col" class="relative py-3.5 px-4">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-neutral-200 dark:divide-neutral-700 dark:bg-neutral-900">
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">Facilitador 1</h2>
                                                <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">
                                                    Dato relevante
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                                Disponible
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center">                                                
                                                <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">
                                                    1
                                                </p>
                                            </div>
                                        </td>
    
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                                {{-- Dependiendo la cantidad maxima / cambiar w-1/6 --}}
                                                <div class="bg-emerald-500 w-1/5 h-1.5"></div> 
                                            </div>
                                        </td>
    
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <flux:modal.trigger name="asignar-facilitador">
                                                <flux:button variant="primary">Asignar</flux:button>
                                            </flux:modal.trigger>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                            <div>
                                                <h2 class="font-medium text-neutral-800 dark:text-white ">Facilitador 2</h2>
                                                <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">
                                                    Dato relevante
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                            <div
                                                class="inline px-3 py-1 text-sm font-normal rounded-full text-red-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                                Ocupado
                                            </div>
                                        </td>
                                        
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="flex items-center">                                                
                                                <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">
                                                    +4
                                                </p>
                                            </div>
                                        </td>
    
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                                {{-- Dependiendo la cantidad maxima / cambiar w-1/6 --}}
                                                <div class="bg-emerald-500 w-5/5 h-1.5"></div> 
                                            </div>
                                        </td>
    
                                        <td class="px-4 py-4 text-sm whitespace-nowrap">
                                            
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
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-neutral-100 dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>
    
                        <span>
                            previous
                        </span>
                    </a>
    
                    <a href="#"
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-neutral-100 dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                        <span>
                            Next
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
      <flux:modal name="asignar-facilitador" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Asignar facilitador</flux:heading>

                <flux:text class="mt-2">
                    <p>Actualmente estás asignado al facilitador: <strong>Nombre del facilitador</strong>.</p>
                    <p>Para la solicitud número: <strong>Número de solicitud</strong>.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Confirmar asignación</flux:button>
            </div>
        </div>
    </flux:modal>

</div>

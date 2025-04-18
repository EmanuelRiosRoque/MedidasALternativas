<div class="relative inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[90px] dark:blur-[120px] rounded-full top-1/2 rigth-1/2 -translate-x-1/2 -translate-y-1/2 z-0 pointer-events-none animate__animated animate__backInRight"></div>
    
    <!-- component -->
    <div class="overflow-hidden  z-10 relative">

    <section class="container px-4 mx-auto z-20 animate__animated animate__fadeInUp">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-neutral-800 dark:text-white">Convenios</h2>

                    <span class="px-3 py-1 text-xs text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/30 dark:text-emerald-300">240 vendors</span>
                </div>

                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-300">Cantidad total de convenios registrados.</p>
            </div>

            <div class="flex items-center mt-4 gap-x-3">
                
                <a wire:navigate href={{ route('convenio.index') }} class="flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-emerald-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-emerald-600 dark:hover:bg-emerald-500 dark:bg-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <span>Nuevo Convenio</span>
                </a>
            </div>
        </div>

        <div class="mt-6 md:flex md:items-center md:justify-between">
            <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-neutral-900 rtl:flex-row-reverse dark:border-neutral-700 dark:divide-neutral-700">
                <button class="px-5 py-2 text-xs font-medium text-neutral-600 transition-colors duration-200 bg-neutral-100 sm:text-sm dark:bg-neutral-800 dark:text-neutral-300">
                    View all
                </button>

                <button class="px-5 py-2 text-xs font-medium text-neutral-600 transition-colors duration-200 sm:text-sm dark:hover:bg-neutral-800 dark:text-neutral-300 hover:bg-neutral-100">
                    Monitored
                </button>
            </div>

            <div class="relative flex items-center mt-4 md:mt-0">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-3 text-neutral-400 dark:text-neutral-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>

                <input type="text" placeholder="Search" class="block w-full py-1.5 pr-5 text-neutral-700 bg-white border border-neutral-200 rounded-lg md:w-80 placeholder-neutral-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-600 focus:border-emerald-400 dark:focus:border-emerald-300 focus:ring-emerald-300 focus:outline-none focus:ring focus:ring-opacity-40">
            </div>
        </div>

        <div class="flex flex-col mt-6">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden border border-neutral-200 dark:border-neutral-700 md:rounded-lg">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-800">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                        <button class="flex items-center gap-x-3 focus:outline-none">
                                            <span>Company</span>

                                            <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                                <path d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z" fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                            </svg>
                                        </button>
                                    </th>

                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                        Status
                                    </th>

                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">
                                        About
                                    </th>

                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">Users</th>

                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-neutral-500 dark:text-neutral-400">License use</th>

                                    <th scope="col" class="relative py-3.5 px-4">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200 dark:divide-neutral-700 dark:bg-neutral-900">
                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div>
                                            <h2 class="font-medium text-neutral-800 dark:text-white ">Catalog</h2>
                                            <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">catalogapp.io</p>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                            Customer
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-neutral-700 dark:text-neutral-200">Content curating app</h4>
                                            <p class="text-neutral-500 dark:text-neutral-400">Brings all your news into one place</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">+4</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                            <div class="bg-emerald-500 w-2/3 h-1.5"></div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300  cursor-pointer hover:text-emerald-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div>
                                            <h2 class="font-medium text-neutral-800 dark:text-white ">Circooles</h2>
                                            <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">getcirooles.com</p>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal text-neutral-500 bg-neutral-100 rounded-full dark:text-neutral-400 gap-x-2 dark:bg-neutral-800">
                                            Churned
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-neutral-700 dark:text-neutral-200">Design software</h4>
                                            <p class="text-neutral-500 dark:text-neutral-400">Super lightweight design app</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">+4</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                            <div class="bg-emerald-500 w-2/5 h-1.5"></div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:bg-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div>
                                            <h2 class="font-medium text-neutral-800 dark:text-white ">Sisyphus</h2>
                                            <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">sisyphus.com</p>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                            Customer
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-neutral-700 dark:text-neutral-200">Automation and workflow</h4>
                                            <p class="text-neutral-500 dark:text-neutral-400">Time tracking, invoicing and expenses</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">+4</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                            <div class="bg-emerald-500 w-11/12 h-1.5"></div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:bg-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div>
                                            <h2 class="font-medium text-neutral-800 dark:text-white ">Hourglass</h2>
                                            <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">hourglass.app</p>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal text-neutral-500 bg-neutral-100 rounded-full dark:text-neutral-400 gap-x-2 dark:bg-neutral-800">
                                            Churned
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-neutral-700 dark:text-neutral-200">Productivity app</h4>
                                            <p class="text-neutral-500 dark:text-neutral-400">Time management and productivity</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">+4</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                            <div class="bg-emerald-500 w-1/3 h-1.5"></div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:bg-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <div>
                                            <h2 class="font-medium text-neutral-800 dark:text-white ">Quotient</h2>
                                            <p class="text-sm font-normal text-neutral-600 dark:text-neutral-400">quotient.co</p>
                                        </div>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-neutral-800">
                                            Customer
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div>
                                            <h4 class="text-neutral-700 dark:text-neutral-200">Sales CRM</h4>
                                            <p class="text-neutral-500 dark:text-neutral-400">Web-based sales doc management</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1256&q=80" alt="">
                                            <img class="object-cover w-6 h-6 -mx-1 border-2 border-white rounded-full dark:border-neutral-700 shrink-0" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=256&q=80" alt="">
                                            <p class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-emerald-600 bg-emerald-100 border-2 border-white rounded-full">+4</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="w-48 h-1.5 bg-emerald-200 overflow-hidden rounded-full">
                                            <div class="bg-emerald-500 w-1/6 h-1.5"></div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <button class="px-1 py-1 text-neutral-500 transition-colors duration-200 rounded-lg dark:text-neutral-300 hover:bg-neutral-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
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
                <a href="#" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-neutral-100 dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                    </svg>

                    <span>
                        previous
                    </span>
                </a>

                <a href="#" class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-neutral-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-neutral-100 dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                    <span>
                        Next
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    </div>
</div>

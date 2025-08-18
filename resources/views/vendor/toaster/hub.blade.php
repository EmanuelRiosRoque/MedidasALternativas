<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))"
    @class([
        'fixed z-50 p-4 w-full flex flex-col pointer-events-none sm:p-6',
        'bottom-0' => $alignment->is('bottom'),
        'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
        'top-0' => $alignment->is('top'),
        'items-start rtl:items-end' => $position->is('left'),
        'items-center' => $position->is('center'),
        'items-end rtl:items-start' => $position->is('right'),
])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
            x-init="
                $nextTick(() => {
                    toast.show($el);
                    const bar = $refs['bar-' + toast.id];
                    if (bar) {
                        bar.style.width = '100%';
                        setTimeout(() => bar.style.width = '0%', toast.timeout);
                    }
                })
            "
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="relative flex items-center gap-3 max-w-sm w-full p-4 rounded-md shadow-md border pointer-events-auto"
            :class="toast.select({
                success: 'bg-white text-black border-gray-200 dark:bg-zinc-900 dark:text-white dark:border-zinc-700',
                error: 'bg-red-600 text-white border-red-700',
                warning: 'bg-white text-black border-red-700 dark:bg-zinc-900 dark:text-white dark:border-zinc-700',
                info: 'bg-blue-500 text-white border-blue-600'
            })"
        >

            <!-- Success icon -->
            <template x-if="toast.type === 'success'">
                <div class="flex-shrink-0 w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </template>

            <template x-if="toast.type === 'warning'">
                <div class="flex-shrink-0 w-6 h-6 bg-red-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </template>
            
            

            <!-- Mensaje -->
            <span class="text-sm flex-1" x-text="toast.message"></span>

            <!-- Botón cerrar -->
            @if($closeable)
            <button @click="toast.dispose()" aria-label="@lang('close')" 
                class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-white">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" 
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 
                        111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 
                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 
                        4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            @endif

            <!-- Barra de progreso -->
            <div class="absolute bottom-0 left-0 w-full h-[3px] overflow-hidden rounded-b"
                :class="{
                    'bg-emerald-100 dark:bg-emerald-900': toast.type === 'success',
                    'bg-red-200 dark:bg-red-900': toast.type === 'warning' || toast.type === 'error',
                    'bg-blue-200 dark:bg-blue-900': toast.type === 'info'
                }">
                <div 
                    :ref="'bar-' + toast.id"
                    class="h-full transition-[width] duration-[3000ms] ease-linear will-change-[width]"
                    :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-red-500': toast.type === 'warning' || toast.type === 'error',
                        'bg-blue-500': toast.type === 'info'
                    }"
                    style="width: 100%">
                </div>
            </div>
        </div>
    </template>
</div>

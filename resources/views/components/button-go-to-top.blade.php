<div 
    x-data="{ show: false }" 
    x-init="window.addEventListener('scroll', () => show = window.scrollY > 300)" 
    x-cloak
>
  <button
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 scale-90"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 scale-90"
    type="button"
    aria-label="Subir"
    title="Subir"
    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="fixed bottom-4 right-4 z-50 h-12 w-12 rounded-full
           flex items-center justify-center shadow-lg
           bg-emerald-600 text-white hover:bg-emerald-700
           focus:outline-none focus:ring-2 focus:ring-emerald-300
           dark:bg-emerald-500 dark:hover:bg-emerald-600 dark:focus:ring-emerald-700"
    style="right: calc(env(safe-area-inset-right, 0px) + 1rem);
           bottom: calc(env(safe-area-inset-bottom, 0px) + 1rem);"
  >
    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
      <path fill-rule="evenodd" d="M3.293 12.707a1 1 0 011.414 0L10 7.414l5.293 5.293a1 1 0 101.414-1.414l-6-6a1 1 0 00-1.414 0l-6 6a1 1 0 001.414 1.414z" clip-rule="evenodd"/>
    </svg>
  </button>
</div>
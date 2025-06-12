<div class="text-center border-b border-emerald-200 dark:border-emerald-700 pb-6 mb-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">
        {{ $title }}
    </h1>
    @if ($slot->isNotEmpty())
        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            {{ $slot }}
        </p>
    @endif
</div>

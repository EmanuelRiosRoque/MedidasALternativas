<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id'] }}')"
    @endif
    class="bg-white  hover:border-emerald-500 dark:bg-neutral-800 text-black dark:text-white border-2 border-gray-300 dark:border-neutral-700 
           rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer"
>

    <p class="text-sm font-semibold truncate">
        {{ $event['title'] }}
    </p>

    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 line-clamp-2">
        {{ $event['description'] ?? 'No description' }}
    </p>

    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 italic">
        {{ $event['hora_inicio'] ? \Carbon\Carbon::parse($event['hora_inicio'])->format('h:i A') : 'Sin hora' }} - {{ $event['hora_fin'] ? \Carbon\Carbon::parse($event['hora_fin'])->format('h:i A') : 'Sin hora' }}
    </p>
</div>


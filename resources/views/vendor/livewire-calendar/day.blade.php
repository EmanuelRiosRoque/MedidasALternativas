@php
    $hoy = \Carbon\Carbon::today();
    $esPasado = $day->lt($hoy);
@endphp

<div
    ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="flex-1 h-44 border border-gray-200 -mt-px -ml-px"
    style="min-width: 10rem;"
>
    <div class="w-full h-full" id="{{ $componentId }}-{{ $day }}">
        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="w-full h-full p-2 flex flex-col
                {{ $dayInMonth 
                    ? ($isToday 
                        ? 'bg-gray-200 dark:bg-gray-300 text-black dark:text-gray-900'
                        : ($esPasado 
                            ? 'bg-emerald-700 dark:bg-emerald-600 text-white' 
                            : 'bg-neutral-700 dark:bg-neutral-800 text-white')) 
                    : 'bg-gray-400 dark:bg-gray-600 text-white' 
                }}"
        >

            {{-- Número del día --}}
            <div class="flex items-center">
                <p class="text-sm {{ $dayInMonth ? 'font-medium' : '' }}">
                    {{ $day->format('j') }}
                </p>
                <p class="text-xs text-gray-800 ml-4">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
            </div>

            {{-- Eventos --}}
            <div class="p-2 my-2 flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 grid-flow-row gap-2">
                    @foreach($events as $event)
                        <div
                            @if($dragAndDropEnabled)
                                draggable="true"
                            @endif
                            ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')"
                        >
                            @include($eventView, [
                                'event' => $event,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

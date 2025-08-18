<div @if($pollMillis !==null && $pollAction !==null) wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !==null) wire:poll.{{ $pollMillis }}ms @endif>
    <div>
        @includeIf($beforeCalendarView)
    </div>
    
<div class="flex flex-wrap items-center justify-between gap-2 mb-4">

    <!-- Botón semana anterior -->
    <button 
        wire:click="goToPreviousWeek"
        @disabled($soloHoy)
        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-white disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition-all duration-200"
    >
        ← Semana anterior
    </button>

    <!-- Botón Vista normal -->
    <button 
        wire:click="resetVista"
        class="px-4 py-2 rounded-xl bg-green-100 hover:bg-green-200 text-green-900 dark:bg-green-800 dark:hover:bg-green-700 dark:text-white shadow-sm transition-all duration-200"
    >
        Vista normal
    </button>

    <!-- Botón Semana completa -->
    <button 
        wire:click="goToWeek"
        class="px-4 py-2 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-900 dark:bg-emerald-800 dark:hover:bg-emerald-700 dark:text-white shadow-sm transition-all duration-200"
    >
        Semana
    </button>

    <!-- Rango de fechas -->
    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center flex-1">
        {{ $gridStartsAt->format('d M Y') }} – {{ $gridEndsAt->format('d M Y') }}
    </h2>

    <!-- Botón Hoy -->
    <button 
        wire:click="goToCurrentWeek"
        class="px-4 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-900 dark:bg-blue-800 dark:hover:bg-blue-700 dark:text-white shadow-sm transition-all duration-200"
    >
        Hoy
    </button>

    <!-- Botón semana siguiente -->
    <button 
        wire:click="goToNextWeek"
        @disabled($soloHoy)
        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:text-white disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition-all duration-200"
    >
        Semana siguiente →
    </button>
</div>

    <div class="flex">
        <div class="overflow-x-auto w-full">
            <div class="inline-block min-w-full">

                {{-- Encabezados de días de la semana (solo lunes a viernes) --}}
                <div class="w-full flex flex-row">
                    @foreach($monthGrid->first() as $day)
                        @if ($day->isoFormat('E') <= 5)
                            @if (!$soloHoy || $day->isToday())
                                @include($dayOfWeekView, ['day' => $day])
                            @endif
                        @endif
                    @endforeach
                </div>

                {{-- Días del mes (solo lunes a viernes) --}}
                @foreach($monthGrid as $week)
                    <div class="w-full flex flex-row">
                        @foreach($week as $day)
                            @if ($day->isoFormat('E') <= 5)
                                @if (!$soloHoy || $day->isToday())
                                    @include($dayView, [
                                        'componentId' => $componentId,
                                        'day' => $day,
                                        'dayInMonth' => $day->isSameMonth($startsAt),
                                        'isToday' => $day->isToday(),
                                        'events' => $getEventsForDay($day, $events),
                                    ])
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endforeach


            </div>
        </div>
    </div>

   
    <div>
        @includeIf($afterCalendarView)
    </div>
</div>
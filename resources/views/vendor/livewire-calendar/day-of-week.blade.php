@php
    $diaSemana = $day->translatedFormat('l'); // Ej: 'lunes', 'martes', etc.
@endphp

@if (in_array(strtolower($diaSemana), ['lunes', 'martes', 'miércoles', 'jueves', 'viernes']))
    <div class="flex-1 h-12 border -mt-px -ml-px flex items-center justify-center bg-emerald-600 text-white dark:bg-neutral-700 dark:text-white"
         style="min-width: 10rem;">

        <p class="text-sm">
            {{ ucfirst($diaSemana) }}
        </p>

    </div>
@endif

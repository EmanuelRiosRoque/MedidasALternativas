<flux:callout variant="sparkles" icon="bell">
    <flux:callout.heading>
        Recordatorio
        <flux:badge color="teal" size="sm" inset="top bottom">De asignación</flux:badge>
    </flux:callout.heading>

    <flux:callout.text>
        <p class="text-sm font-semibold dark:text-neutral-200 mb-2">¿A quién se te asignó darle sesión?</p>
        <ul class="list-disc list-inside text-sm dark:text-neutral-100 space-y-1">
            <li>
                Para esta pre mediación se asignó a:
                <span class="text-emerald-600 font-semibold">
                    {{ $evento->opcion_invitacion ?? 'Sin asignar' }}
                </span>.
            </li>
            <li>
                Con fecha de atención:
                <span class="text-emerald-600 font-semibold">
                    {{$evento && $evento->fecha ? \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') : 'Sin asignar'
                    }}
                </span>.
            </li>
            <li>
                Horario asignado:
                @if ($evento?->opcion_invitacion === 'separados')
                    <br>
                    <span class="text-emerald-600 font-semibold">
                        {{$evento && $evento->hora_inicio ? \Carbon\Carbon::parse($evento->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$evento && $evento->hora_fin ? \Carbon\Carbon::parse($evento->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                        - Solicitantes
                    </span>.
                    <br>
                    <span class="text-emerald-600 font-semibold">
                        {{$evento && $evento->hora_inicio_invitado ?
                        \Carbon\Carbon::parse($evento->hora_inicio_invitado)->format('g:i a') : 'Sin asignar' }} a
                        {{$evento && $evento->hora_fin_invitado ?
                        \Carbon\Carbon::parse($evento->hora_fin_invitado)->format('g:i a') : 'Sin asignar' }}
                        - Invitados
                    </span>.
                @else
                    <span class="text-emerald-600 font-semibold">
                        {{$evento && $evento->hora_inicio ? \Carbon\Carbon::parse($evento->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$evento && $evento->hora_fin ? \Carbon\Carbon::parse($evento->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                    </span>.
                @endif
            </li>
            @if ($solicitud->modalidad == 'linea')
            <livewire:solicitud.invitacion-evento :evento="$evento" />
            @endif

        </ul>
    </flux:callout.text>
</flux:callout>
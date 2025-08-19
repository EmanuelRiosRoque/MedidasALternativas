<flux:callout variant="sparkles" icon="bell">
    <flux:callout.heading>
        Recordatorio
        <flux:badge color="yellow" size="sm" inset="top bottom">De segunda invitación</flux:badge>
    </flux:callout.heading>

    <flux:callout.text>
        <p class="text-sm font-semibold dark:text-neutral-200 mb-2">¿A quién se te asignó darle sesión?</p>
        <ul class="list-disc list-inside text-sm dark:text-neutral-100 space-y-1">
            <li>
                Para esta pre mediación se asignó a:
                <span class="text-yellow-600 font-semibold">
                    {{ $segSesion->opcion_invitacion ?? 'Sin asignar' }}
                </span>.
            </li>
            <li>
                Con fecha de atención:
                <span class="text-yellow-600 font-semibold">
                    {{$segSesion && $segSesion->fecha ? \Carbon\Carbon::parse($segSesion->fecha)->format('d/m/Y') : 'Sin asignar'
                    }}
                </span>.
            </li>
            <li>
                Horario asignado:
                @if ($segSesion?->opcion_invitacion === 'separados')
                    <br>
                    <span class="text-yellow-600 font-semibold">
                        {{$segSesion && $segSesion->hora_inicio ? \Carbon\Carbon::parse($segSesion->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$segSesion && $segSesion->hora_fin ? \Carbon\Carbon::parse($segSesion->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                        - Solicitantes
                    </span>.
                    <br>
                    <span class="text-yellow-600 font-semibold">
                        {{$segSesion && $segSesion->hora_inicio_invitado ?
                        \Carbon\Carbon::parse($segSesion->hora_inicio_invitado)->format('g:i a') : 'Sin asignar' }} a
                        {{$segSesion && $segSesion->hora_fin_invitado ?
                        \Carbon\Carbon::parse($segSesion->hora_fin_invitado)->format('g:i a') : 'Sin asignar' }}
                        - Invitados
                    </span>.
                @else
                    <span class="text-yellow-600 font-semibold">
                        {{$segSesion && $segSesion->hora_inicio ? \Carbon\Carbon::parse($segSesion->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$segSesion && $segSesion->hora_fin ? \Carbon\Carbon::parse($segSesion->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                    </span>.
                @endif
            </li>
        </ul>
    </flux:callout.text>
</flux:callout>
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
                    {{ $evento->acudira_juntos ? 'Solicitante e Invitado (juntos)' : 'Por separado' }}
                </span>.
            </li>
            <li>
                Con fecha de atención:
                <span class="text-yellow-600 font-semibold">
                    {{$invitacion2 && $invitacion2->fecha_atencion ? \Carbon\Carbon::parse($invitacion2->fecha_atencion)->format('d/m/Y') : 'Sin asignar'
                    }}
                </span>.
            </li>
            <li>
                Horario asignado:
                @if ($invitacion2?->acudira_juntos == 0)
                    <br>
                    <span class="text-yellow-600 font-semibold">
                        {{$invitacion2 && $invitacion2->hora_inicio ? \Carbon\Carbon::parse($invitacion2->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$invitacion2 && $invitacion2->hora_fin ? \Carbon\Carbon::parse($invitacion2->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                        - Solicitantes
                    </span>.
                    <br>
                    <span class="text-yellow-600 font-semibold">
                        {{$invitacion2 && $invitacion2->hora_inicio_invitado ?
                        \Carbon\Carbon::parse($invitacion2->hora_inicio_invitado)->format('g:i a') : 'Sin asignar' }} a
                        {{$invitacion2 && $invitacion2->hora_fin_invitado ?
                        \Carbon\Carbon::parse($invitacion2->hora_fin_invitado)->format('g:i a') : 'Sin asignar' }}
                        - Invitados
                    </span>.
                @else
                    <span class="text-yellow-600 font-semibold">
                        {{$invitacion2 && $invitacion2->hora_inicio ? \Carbon\Carbon::parse($invitacion2->hora_inicio)->format('g:i a') :
                        'Sin asignar' }} a
                        {{$invitacion2 && $invitacion2->hora_fin ? \Carbon\Carbon::parse($invitacion2->hora_fin)->format('g:i a') : 'Sin
                        asignar' }}
                    </span>.
                @endif
            </li>
        </ul>
    </flux:callout.text>
</flux:callout>
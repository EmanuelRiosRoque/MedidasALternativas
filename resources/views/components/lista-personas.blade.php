<div class="w-full flex justify-center py-5 md:py-10 px-4 bg-white dark:bg-neutral-900">
    <div
        class="p-4 w-full max-w-md bg-white dark:bg-neutral-800 rounded-lg shadow-md sm:p-8 border border-neutral-300 dark:border-neutral-700 hover:border-emerald-600 transform transition duration-300 ease-in-out hover:scale-[1.02]">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-xl font-bold leading-none text-neutral-900 dark:text-white">Lista {{ $titulo }}</h3>
            </div>

            <div class="inline-flex items-center text-base font-semibold text-neutral-900 dark:text-white">
                <a href="{{ route('solicitud.personas', ['solicitudId' => $solicitudId]) }}">
                    <flux:button variant="primary">
                        Ver {{ $titulo }}
                    </flux:button>
                </a>
            </div>
        </div>
        <div class="flow-root">
            <ul role="list" class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach ($personas as $persona)
                <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <flux:icon.user-circle class="size-8" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">
                                @if ($persona->persona === 'fisica' || $persona->persona === 'familiar')
                                {{ $persona->nombre }} {{ $persona->apellido_p ?? '' }} {{ $persona->apellido_m ?? '' }}
                                @else
                                {{ $persona->razon_social ?? 'Sin razón social' }}
                                @endif
                            </p>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 truncate">
                                {{ $persona->correos->first()?->email ?? 'Sin correo' }}
                            </p>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

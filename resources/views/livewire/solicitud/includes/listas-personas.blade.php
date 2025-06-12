<div class="grid grid-cols-1 md:grid-cols-3 md:gap-1 gap-2">
    <x-lista-personas :$solicitudId :personas="$solicitantes" titulo="Solicitantes" />

    <div class="mx-auto right-0  mt-9 w-60">
        <div class="shadow-lg">
            <div
                class="text-center p-4 w-full max-w-md bg-white rounded-lg shadow-md sm:p-8 dark:bg-neutral-800 dark:border-neutral-700 border hover:border-emerald-600 transform transition duration-300 ease-in-out hover:scale-[1.02] ">
                <svg aria-hidden="true" role="img" class="h-24 w-24 text-white rounded-full mx-auto" width="32"
                    height="32" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                    <path fill="currentColor"
                        d="M172 120a44 44 0 1 1-44-44a44 44 0 0 1 44 44Zm60 8A104 104 0 1 1 128 24a104.2 104.2 0 0 1 104 104Zm-16 0a88 88 0 1 0-153.8 58.4a81.3 81.3 0 0 1 24.5-23a59.7 59.7 0 0 0 82.6 0a81.3 81.3 0 0 1 24.5 23A87.6 87.6 0 0 0 216 128Z">
                    </path>
                </svg>
                <p class="pt-2 text-lg font-semibold text-neutral-50">{{ $solicitud->facilitador->nombre ?? 'Sin
                    asignar' }}</p>
                <p class="pt-2 text-sm font-semibold text-neutral-100">Facilitador</p>
            </div>
        </div>
    </div>

    <x-lista-personas :$solicitudId :personas="$invitados" titulo="Invitados" />
</div>
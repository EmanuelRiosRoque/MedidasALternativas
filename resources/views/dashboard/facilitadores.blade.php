<div class="relative grid min-h-[95vh] place-items-center px-4 bg-gradient-to-br from-white to-gray-100 dark:from-gray-800 dark:to-gray-900 overflow-hidden">

    {{-- 🔵 Círculo con blur tipo glow teal justo detrás del contenedor --}}
    <div class="absolute w-[500px] h-[500px] bg-teal-500/40 blur-[120px] rounded-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn"></div>
    
    {{-- Contenedor principal con fondo sólido --}}
    <div class="relative z-10 w-full max-w-4xl rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 bg-white dark:bg-gray-900 p-6 sm:p-8 lg:p-10 animate__animated animate__fadeIn">
        <div class="text-center space-y-6">

            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white leading-tight">
                Bienvenido al Sistema de Registro de Personas Facilitadoras
            </h1>
            <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Esta plataforma ha sido diseñada para el uso exclusivo del <strong>Poder Judicial de la Ciudad de México</strong>, permitiendo la gestión eficiente, segura y transparente de el registro de personas facilitadoras.
            </p>
            <div class="mt-6">
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-block px-8 py-3 bg-teal-800 text-white font-semibold rounded-full shadow-lg hover:bg-teal-900 hover:scale-105 transition transform duration-300"
                >
                    Acceder al Panel
                </a>
            </div>
        </div>
    </div>
</div>
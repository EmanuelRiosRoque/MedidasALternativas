<div class="relative grid min-h-[95vh] place-items-center px-4 bg-gradient-to-br from-white to-neutral-100 dark:from-neutral-800 dark:to-neutral-900 overflow-hidden">

    {{-- 🔵 Círculo con blur tipo glow emerald --}}
    <div class="absolute w-[500px] h-[500px] bg-emerald-500/40 blur-[120px] rounded-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-0 animate__animated animate__bounceIn"></div>

    <section class="relative z-10 w-full">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-16 items-center min-h-[90vh]">

                <div class="text-center lg:text-left space-y-6 animate__animated animate__fadeInUp">
                    <div class="flex items-center gap-4 justify-center lg:justify-start mb-2">
                        <div class="relative inline-block">
                            {{-- 🔵 Círculo blur detrás del logo --}}
                            <div class="absolute inset-0 w-12 h-12 bg-emerald-400/30 blur-[30px] rounded-full -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>
                
                            {{-- Logo --}}
                            <img src="{{ asset('img/hero/logo.png') }}" alt="Logo" class="h-8 w-auto relative z-10">
                        </div>
                
                        <span class="inline-flex items-center text-sm font-semibold text-emerald-700 bg-emerald-100 px-3 py-1 rounded-full dark:bg-emerald-900/30 dark:text-emerald-300">
                            💼  Alpha — v0.0.1
                        </span>
                    </div>
                    

                    <h1 class="text-4xl sm:text-5xl font-extrabold text-neutral-900 dark:text-white tracking-tight">
                        ¡Hola, {{ ucwords(strtolower(Auth::user()->name)) }}! <br class="hidden sm:inline" />
                        Bienvenido al Sistema de Medidas Alternativas
                    </h1>
                    
                    
                    @php
                        $prueba = true;
                    @endphp

                    <p class="text-lg text-neutral-600 dark:text-neutral-300 max-w-xl mx-auto lg:mx-0">
                        @if ($prueba)
                        Esta es una versión de prueba sin funcionalidades activas. Actualmente, la plataforma es meramente visual.  
                        Es posible que algunas partes tarden un poco en cargar, ya que el sitio no se encuentra aún en un servidor exclusivo para este proyecto.
                        @else
                            Plataforma exclusiva para el Poder Judicial de la Ciudad de México que permite la gestión eficiente, segura y transparente de las medidas alternativas.
                        @endif
                    </p>


                    <div class="flex justify-center lg:justify-start gap-4">
                        <a
                            href="{{ route('solicitud.create') }}"
                            class="inline-flex items-center justify-center bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-6 py-3 mt-5 rounded-lg shadow-lg hover:scale-105 transition-all duration-300 text-sm"
                        >                      
                            @if ($prueba)
                                Ver demo
                            @else
                                Empezar Ahora !
                            @endif
                        </a>

                        <a
                            href="#"
                            class="inline-flex items-center justify-center rounded-md px-6 py-3 mt-5 text-sm font-semibold text-emerald-700 hover:underline"
                        >
                            Ver documentacion →
                        </a>
                    </div>
                </div>

                {{-- Imagen de interfaz --}}  
                
                <div class="relative animate__animated animate__fadeInUp">
                    <div class="mx-auto w-full max-w-xl overflow-hidden animate-float">
                        <img src="{{ asset('img/hero/mediador2.png') }}" alt="Vista previa del sistema" class="w-full h-auto">
                    </div>
                </div>
                

            </div>
        </div>
    </section>
</div>
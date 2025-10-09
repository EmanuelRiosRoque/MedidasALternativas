{{-- INVITACIONES + CJA (CJA integrado; Observaciones aparte) --}}
<section x-data class="space-y-8">

  {{-- ENCABEZADO + LEYENDA --}}
  <header class="flex items-center justify-between gap-4 flex-wrap">
    <div>
      <h2 class="text-xl font-semibold text-neutral-900 dark:text-white">Invitaciones</h2>
      <p class="text-sm text-neutral-500 dark:text-neutral-400">Gestiona 1ª y 2ª invitación + CJA (solicitante/invitado).</p>
    </div>

    <div class="flex items-center gap-3">
      <span class="inline-flex items-center gap-2 rounded-full
                   bg-blue-100 text-blue-800
                   dark:bg-blue-400/20 dark:text-blue-100
                   ring-1 ring-blue-200 dark:ring-blue-500/30
                   px-3 py-1 text-xs font-semibold">
        <flux:icon.user class="size-4" />
        Solicitante
      </span>
      <span class="inline-flex items-center gap-2 rounded-full
                   bg-emerald-100 text-emerald-800
                   dark:bg-emerald-400/20 dark:text-emerald-100
                   ring-1 ring-emerald-200 dark:ring-emerald-500/30
                   px-3 py-1 text-xs font-semibold">
        <flux:icon.user class="size-4" />
        Invitado
      </span>
    </div>
  </header>

  {{-- ============ TARJETA UNIFICADA: PRIMERA + SEGUNDA INVITACIÓN + CJA (sin observaciones) ============ --}}
  <div class="rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-xl p-6 space-y-8">

    {{-- ===== PRIMERA INVITACIÓN ===== --}}
    <div class="space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
            <flux:icon.calendar class="size-4" />
            Primera invitación
          </span>
        </div>
      </div>

      {{-- GRID: 3/12 Solicitante, 9/12 Invitado --}}
      <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- SOLICITANTE - 1RA (25%) --}}
        <div class="md:col-span-3 h-full rounded-xl p-5">
          <div class="mb-3">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-blue-100 text-blue-800
                         dark:bg-blue-400/20 dark:text-blue-100
                         ring-1 ring-blue-200 dark:ring-blue-500/30
                         px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Solicitante
            </span>
          </div>

          <div class="space-y-3">
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">1era. Fecha de envío</label>
              <div class="sm:col-span-3">
                <flux:input type="date" wire:model.defer="primera.fecha_envio_solicitante" />
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Medio de envío</label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="primera.medio_envio_solicitante" value="personal" label="Personal" />
                  <flux:radio wire:model="primera.medio_envio_solicitante" value="sepomex" label="SEPOMEX" />
                </div>
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta la mediación?</label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="primera.acepta_mediacion_solicitante" value="1" label="Sí" />
                  <flux:radio wire:model="primera.acepta_mediacion_solicitante" value="0" label="No" />
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- INVITADO - 1RA (75%) EN COLUMNAS L→R --}}
        <div class="md:col-span-9 h-full rounded-xl p-5 ring-1 ring-emerald-200/60 dark:ring-emerald-700/40 bg-emerald-50/60 dark:bg-emerald-900/10 border-l-4 border-emerald-500">
          <div class="mb-4">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-emerald-100 text-emerald-800
                         dark:bg-emerald-400/20 dark:text-emerald-100
                         ring-1 ring-emerald-200 dark:ring-emerald-500/30
                         px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Invitado
            </span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Col 1: Programación --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <flux:icon.clock class="size-4" /> Programación
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Se le espera el día</label>
                  <div class="sm:col-span-3">
                    <flux:input type="date" wire:model.defer="primera.fecha_sesion_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                  <div class="sm:col-span-3">
                    <flux:input type="time" wire:model.defer="horaInicioInvitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Atendió a la 1ra sesión</label>
                  <div class="sm:col-span-3">
                    <div class="mt-1 flex flex-wrap items-center gap-4">
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="1" label="Sí" />
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="0" label="No" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Col 2: Asistencia real --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <flux:icon.clipboard-document-check class="size-4" /> Asistencia real
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Fecha en que asiste</label>
                  <div class="sm:col-span-3">
                    <flux:input type="date" wire:model.defer="primera.fecha_asistencia_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                  <div class="sm:col-span-3">
                    <flux:input type="time" wire:model.defer="primera.hora_asistencia_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta la mediación?</label>
                  <div class="sm:col-span-3">
                    <div class="mt-1 flex flex-wrap items-center gap-4">
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="1" label="Sí" />
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="0" label="No" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Col 3: Datos --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <svg class="h-4 w-4 text-emerald-500 dark:text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7" /></svg>
                Datos
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Nombre del invitado</label>
                  <div class="sm:col-span-3">
                    <flux:input wire:model.defer="primera.nombre_invitado" />
                  </div>
                </div>
              </div>
            </div>
          </div> {{-- /grid columnas --}}
        </div>
      </div>
    </div>

    {{-- SEPARADOR --}}
    <div class="border-t border-neutral-200 dark:border-neutral-800"></div>

    {{-- ===== SEGUNDA INVITACIÓN ===== --}}
    <div class="space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1 text-sm font-semibold text-emerald-700 dark:text-emerald-300">
            <flux:icon.calendar class="size-4" />
            Segunda invitación
          </span>
        </div>
      </div>

      {{-- GRID: 3/12 Solicitante, 9/12 Invitado --}}
      <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- SOLICITANTE - 2DA (25%) --}}
        <div class="md:col-span-3 h-full rounded-xl p-5">
          <div class="mb-3">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-blue-100 text-blue-800
                         dark:bg-blue-400/20 dark:text-blue-100
                         ring-1 ring-blue-200 dark:ring-blue-500/30
                         px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Solicitante
            </span>
          </div>

          <div class="space-y-3">
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">1era. Fecha de envío</label>
              <div class="sm:col-span-3">
                <flux:input type="date" wire:model.defer="primera.fecha_envio_solicitante" />
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Medio de envío</label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="primera.medio_envio_solicitante" value="personal" label="Personal" />
                  <flux:radio wire:model="primera.medio_envio_solicitante" value="sepomex" label="SEPOMEX" />
                </div>
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta la mediación?</label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="primera.acepta_mediacion_solicitante" value="1" label="Sí" />
                  <flux:radio wire:model="primera.acepta_mediacion_solicitante" value="0" label="No" />
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- INVITADO - 2DA (75%) EN COLUMNAS L→R --}}
        <div class="md:col-span-9 h-full rounded-xl p-5 ring-1 ring-emerald-200/60 dark:ring-emerald-700/40 bg-emerald-50/60 dark:bg-emerald-900/10 border-l-4 border-emerald-500">
          <div class="mb-4">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-emerald-100 text-emerald-800
                         dark:bg-emerald-400/20 dark:text-emerald-100
                         ring-1 ring-emerald-200 dark:ring-emerald-500/30
                         px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Invitado
            </span>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Col 1: Programación --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <flux:icon.clock class="size-4" /> Programación
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Se le espera el día</label>
                  <div class="sm:col-span-3">
                    <flux:input type="date" wire:model.defer="primera.fecha_sesion_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                  <div class="sm:col-span-3">
                    <flux:input type="time" wire:model.defer="horaInicioInvitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Atendió a la 2da sesión</label>
                  <div class="sm:col-span-3">
                    <div class="mt-1 flex flex-wrap items-center gap-4">
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="1" label="Sí" />
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="0" label="No" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Col 2: Asistencia real --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <flux:icon.clipboard-document-check class="size-4" /> Asistencia real
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Fecha en que asiste</label>
                  <div class="sm:col-span-3">
                    <flux:input type="date" wire:model.defer="primera.fecha_asistencia_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Hora</label>
                  <div class="sm:col-span-3">
                    <flux:input type="time" wire:model.defer="primera.hora_asistencia_invitado" />
                  </div>
                </div>
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">¿Acepta la mediación?</label>
                  <div class="sm:col-span-3">
                    <div class="mt-1 flex flex-wrap items-center gap-4">
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="1" label="Sí" />
                      <flux:radio wire:model="primera.acepta_mediacion_invitado" value="0" label="No" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Col 3: Datos --}}
            <div>
              <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 mb-2">
                <svg class="h-4 w-4 text-emerald-500 dark:text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7" /></svg>
                Datos
                <div class="flex-1 h-px bg-emerald-200/60 dark:bg-emerald-800/40"></div>
              </div>
              <div class="space-y-4">
                <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
                  <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">Nombre del invitado</label>
                  <div class="sm:col-span-3">
                    <flux:input wire:model.defer="primera.nombre_invitado" />
                  </div>
                </div>
              </div>
            </div>
          </div> {{-- /grid columnas --}}
        </div>
      </div>
    </div>

    {{-- ===== CJA (SOLO Solicitante e Invitado) INTEGRADO EN ESTA MISMA TARJETA ===== --}}
    <div class="space-y-6">
      <div class="flex flex-wrap items-center justify-between gap-3 -mx-6 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-2 rounded-xl
                      bg-neutral-100 text-neutral-800
                      dark:bg-neutral-700/40 dark:text-neutral-100
                      ring-1 ring-neutral-200 dark:ring-neutral-600/40
                      px-3 py-1 text-sm font-semibold">
            Centro de Justicia Alternativa
          </span>
        </div>
      </div>

      {{-- GRID 50/50 --}}
      <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

        {{-- CJA: SOLICITANTE --}}
        <div class="md:col-span-6 h-full rounded-xl p-5">
          <div class="mb-3">
            <span class="inline-flex items-center gap-2 rounded-full
                        bg-blue-100 text-blue-800
                        dark:bg-blue-400/20 dark:text-blue-100
                        ring-1 ring-blue-200 dark:ring-blue-500/30
                        px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Solicitante
            </span>
          </div>

          <div class="space-y-3">
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                Propuesta de inicio mediación
              </label>
              <div class="sm:col-span-3">
                <flux:input type="date" wire:model.defer="cja.propuesta_inicio_fecha" />
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                Hora propuesta
              </label>
              <div class="sm:col-span-3">
                <flux:input type="time" wire:model.defer="cja.propuesta_inicio_hora" />
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                ¿Acepta inicio?
              </label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="cja.acepta_inicio_solicitante" value="1" label="Sí" />
                  <flux:radio wire:model="cja.acepta_inicio_solicitante" value="0" label="No" />
                </div>
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                Fecha vencimiento
              </label>
              <div class="sm:col-span-3">
                <flux:input type="date" wire:model.defer="cja.fecha_vencimiento" />
              </div>
            </div>
          </div>
          
        </div>

        {{-- CJA: INVITADO --}}
        <div class="md:col-span-6 h-full rounded-xl p-5 ring-1 ring-yellow-200/60 dark:ring-yellow-700/40 bg-yellow-50/60 dark:bg-yellow-900/10 border-l-4 border-yellow-500">
          <div class="mb-3">
            <span class="inline-flex items-center gap-2 rounded-full
                        bg-yellow-100 text-yellow-800
                        dark:bg-yellow-400/20 dark:text-yellow-100
                        ring-1 ring-yellow-200 dark:ring-yellow-500/30
                        px-2.5 py-0.5 text-xs font-semibold">
              <flux:icon.user class="size-4" /> Invitado
            </span>
          </div>

          <div class="space-y-3">
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                Fecha en que es propuesta
              </label>
              <div class="sm:col-span-3">
                <flux:input type="date" wire:model.defer="cja.fecha_propuesta_invitado" />
              </div>
            </div>
            <div class="sm:grid sm:grid-cols-5 sm:gap-3 sm:items-center">
              <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
                ¿Acepta inicio?
              </label>
              <div class="sm:col-span-3">
                <div class="mt-1 flex flex-wrap items-center gap-4">
                  <flux:radio wire:model="cja.acepta_inicio_invitado" value="1" label="Sí" />
                  <flux:radio wire:model="cja.acepta_inicio_invitado" value="0" label="No" />
                </div>
              </div>
            </div>
          </div>

         
        </div>

      </div>
    </div>

    {{-- Footer general del bloque unificado (Invitaciones + CJA sin observaciones) --}}
    <div class="pt-2">
      <div class="flex justify-end">
        <flux:button variant="primary" wire:click="guardarInvitacionesYCja">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
              <path d="M17 21v-8H7v8"/>
              <path d="M7 3v5h8"/>
            </svg>
            Grabar todo
          </span>
        </flux:button>
      </div>
    </div>

  </div> {{-- /Tarjeta unificada --}}

  {{-- ============ OBSERVACIONES (SECCIÓN APARTE) ============ --}}
  <div class="rounded-2xl bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 shadow-xl">
    <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4 border-b border-neutral-200 dark:border-neutral-800">
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center gap-2 rounded-xl
                    bg-neutral-100 text-neutral-800
                    dark:bg-neutral-700/40 dark:text-neutral-100
                    ring-1 ring-neutral-200 dark:ring-neutral-600/40
                    px-3 py-1 text-sm font-semibold">
          Observaciones
        </span>
      </div>
    </div>

    <div class="p-6 space-y-4">
      <div class="sm:grid sm:grid-cols-5 sm:gap-4 sm:items-center">
        <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 sm:col-span-2">
          Fecha de observación
        </label>
        <div class="sm:col-span-3">
          <flux:input type="date" wire:model.defer="cja.fecha_observacion" />
        </div>
      </div>

      <div>
        <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-2">
          Observaciones
        </label>
        <textarea
          wire:model.defer="cja.observaciones"
          rows="3"
          class="w-full rounded-lg bg-white dark:bg-neutral-900 ring-1 ring-neutral-200 dark:ring-neutral-700 px-3 py-2 text-sm text-neutral-800 dark:text-neutral-100 outline-none focus:ring-2 focus:ring-indigo-400/60"></textarea>
      </div>

      <div class="pt-2 flex justify-end">
        <flux:button variant="primary" wire:click="guardarCjaObservaciones">
          <span class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
              <path d="M17 21v-8H7v8"/>
              <path d="M7 3v5h8"/>
            </svg>
            Grabar observaciones
          </span>
        </flux:button>
      </div>
    </div>
  </div>

</section>

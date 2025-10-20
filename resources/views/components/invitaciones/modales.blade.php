<flux:modal name="cancelar" class="min-w-[22rem]">
    <div class="space-y-6">
        {{-- ENCABEZADO --}}
        <div>
            <flux:heading size="lg">Cancelar solicitud</flux:heading>

            <flux:text class="mt-2 text-sm text-neutral-600 dark:text-neutral-300">
                <p>Estás a punto de cancelar esta solicitud de mediación.</p>
                <p>Por favor selecciona el motivo de cancelación y el personal que realiza la acción.</p>
            </flux:text>
        </div>

        {{-- MOTIVO DE CANCELACIÓN --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
                Motivo de cancelación
            </label>
            <flux:select placeholder="Seleccione un motivo..." wire:model.defer="mediacion.forma_concluir">
                @foreach ($cat_cancelaciones as $cancelacion)
                    <flux:select.option value="{{ $cancelacion->id }}" label="{{ $cancelacion->motivo }}" />
                @endforeach
            </flux:select>
        </div>

        {{-- PERSONAL QUE CANCELA --}}
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-neutral-700 dark:text-neutral-200 mb-1">
                Personal que realiza la cancelación
            </label>
            <flux:select placeholder="Seleccione personal..." wire:model.defer="mediacion.personal_concluye">
                @foreach ($facilitadores as $facilitador)
                    <flux:select.option value="{{ $facilitador->id }}" label="{{ $facilitador->nombre }}" />
                @endforeach
            </flux:select>
        </div>

        {{-- BOTONES --}}
        <div class="flex gap-2">
            <flux:spacer />

            <flux:modal.close>
                <flux:button variant="ghost" size="sm"
                    class="!text-neutral-700 hover:!bg-neutral-100 dark:!text-neutral-200 dark:hover:!bg-neutral-800">
                    Regresar
                </flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="danger" size="sm"
                class="!bg-red-600 hover:!bg-red-700 !text-white focus:!ring-2 focus:!ring-red-200 dark:focus:!ring-red-500/30">
                Confirmar cancelación
            </flux:button>
        </div>
    </div>
</flux:modal>
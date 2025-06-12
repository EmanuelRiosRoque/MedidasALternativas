<div>
    @if ($evento)
        <div class="flex flex-col">
            @if (!$evento->url)
            <div wire:loading.remove wire:target="store">
                <div class="space-y-4">
                    <flux:input label="Enlace / Liga" placeholder="Enlace de reunión virtual" wire:model="enlaceReunion" />
    
                    <div class="flex justify-end">
                        <flux:button wire:click="store" variant="primary">
                            Enviar invitación
                        </flux:button>
                    </div>
                </div>
            </div>

            <div wire:loading wire:target="store" class="mt-4">
                <div class="flex justify-center items-center">
                    <div class="loader inline-block"></div>
                </div>
            </div>
            @else
            <flux:callout icon="computer-desktop" color="emerald">
                <flux:callout.heading>Esta solicitud ya tiene una liga asignada</flux:callout.heading>
                <flux:callout.text>
                    Esta es la liga de la sesión:
                    <flux:callout.link href="{{ $evento->url }}" target="_blank">
                        Ir a la sesión
                    </flux:callout.link>
                </flux:callout.text>
            </flux:callout>
            @endif
        </div>
    @else
        <p class="text-sm text-white italic flex justify-center items-center">
            Aún no se ha asignado un evento.
        </p>
    @endif
</div>

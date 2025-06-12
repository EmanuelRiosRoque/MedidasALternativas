<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvitacionEvento extends Notification
{
    use Queueable;

    public function __construct()
    {
        // Puedes pasar parámetros si los necesitas
    }

    /**
     * Define los canales de entrega.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Representación del correo.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $avisoPath = public_path('pdfs/aviso_civil.pdf');
        $privacidadPath = public_path('pdfs/formato-privacidad.pdf');

        return (new MailMessage)
            ->subject('Invitación a la reunión')
            ->greeting('Hola')
            ->line('Comparto la liga de acceso a la Reunión de Google Meet programada para atenderle en el Procedimiento de Mediación a distancia.')
            ->line('Adjunto encontrará los documentos de Aviso de Privacidad y Reglas del Procedimiento para su conocimiento.')
            ->line('Le solicito atentamente que me envíe los siguientes documentos en PDF:')
            ->line('- Documento que acredite su identidad')
            ->line('- Documento que, en su caso, acredite su personalidad jurídica')
            ->line('- Documentos que acrediten su relación jurídica en conflicto')
            ->line('Estos documentos deberán ser exhibidos en original al inicio de la reunión para verificarlos a través de la cámara.')
            ->action('Unirse a la reunión', url('https://zoom.us/j/123456'))
            ->line('Quedo a su disposición para cualquier duda o comentario.')
            ->attach($avisoPath, [
                'as' => 'Aviso_de_Privacidad.pdf',
                'mime' => 'application/pdf',
            ])
            ->attach($privacidadPath, [
                'as' => 'Reglas_del_Procedimiento.pdf',
                'mime' => 'application/pdf',
            ]);
    }


    /**
     * Representación en base de datos (opcional).
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}

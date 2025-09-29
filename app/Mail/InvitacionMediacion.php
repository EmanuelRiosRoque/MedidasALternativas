<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class InvitacionMediacion extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $enlace;
    public string $horario;
    public string $fecha;

    public ?int $numInvitacion;
    public ?int $tipoInvitacion;

    public function __construct(string $nombre, string $enlace, string $horario, string $fecha, ?int $numInvitacion = null, ?int $tipoInvitacion = null)
    {
        $this->nombre = $nombre;
        $this->enlace = $enlace;
        $this->horario = $horario;
        $this->fecha = $fecha;
        $this->numInvitacion = $numInvitacion;
        $this->tipoInvitacion = $tipoInvitacion;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitación a la reunión de mediación',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invitacion',
            with: [
                'nombre' => $this->nombre,
                'enlace' => $this->enlace,
                'horario' => $this->horario,
                'fecha' => $this->fecha,
                'numInvitacion' => $this->numInvitacion,
                'tipoInvitacion' => $this->tipoInvitacion,
            ]
        );
    }



    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path('pdfs/aviso_civil.pdf'))
                ->as('Aviso_de_Privacidad.pdf')
                ->withMime('application/pdf'),

            Attachment::fromPath(public_path('pdfs/formato-privacidad.pdf'))
                ->as('Reglas_del_Procedimiento.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

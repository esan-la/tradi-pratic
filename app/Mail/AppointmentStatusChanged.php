<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Appointment $rendezVous;
    public string $oldStatus;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 30;

    public function __construct(Appointment $rendezVous, string $oldStatus)
    {
        $this->rendezVous = $rendezVous;
        $this->oldStatus = $oldStatus;
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        $statusLabels = [
            'confirme' => 'confirmé ✅',
            'annule' => 'annulé ❌',
            'termine' => 'terminé ✔️',
            'reporte' => 'reporté 🔄',
        ];

        $status = $statusLabels[$this->rendezVous->statut] ?? $this->rendezVous->statut;

        return new Envelope(
            subject: "Rendez-vous {$status} - TradiPratic",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointment-status',
            with: [
                'rendezVous' => $this->rendezVous,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->rendezVous->statut,
            ],
        );
    }
}

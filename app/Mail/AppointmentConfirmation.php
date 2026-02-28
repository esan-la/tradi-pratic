<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Appointment $rendezVous;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 30;

    public function __construct(Appointment $rendezVous)
    {
        $this->rendezVous = $rendezVous;
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre rendez-vous - TradiPratic 📅',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointment-confirmation',
            with: [
                'rendezVous' => $this->rendezVous,
                'service' => $this->rendezVous->service,
                'prestataire' => $this->rendezVous->service->user,
            ],
        );
    }
}

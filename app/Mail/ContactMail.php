<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 30;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message de contact - TradiPratic 📩',
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact',
            with: [
                'contactName' => $this->data['name'],
                'contactEmail' => $this->data['email'],
                'contactMessage' => $this->data['message'],
                'contactSubject' => $this->data['subject'] ?? 'Sans objet',
            ],
        );
    }
}

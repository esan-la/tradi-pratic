<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;
    public int $timeout = 30;

    protected string $to;
    protected Mailable $mailable;

    public function __construct(string $to, Mailable $mailable)
    {
        $this->to = $to;
        $this->mailable = $mailable;
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        try {
            Mail::to($this->to)->send($this->mailable);
            Log::info("Email envoyé avec succès à {$this->to}");
        } catch (\Exception $e) {
            Log::error("Échec envoi email à {$this->to}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Job SendEmailJob échoué définitivement pour {$this->to}: " . $exception->getMessage());
    }
}

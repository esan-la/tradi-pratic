<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentStatusChanged;
use App\Mail\ContactMail;
use App\Mail\WelcomeMail;
use App\Mail\ResetPasswordMail;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Envoyer email de bienvenue
     */
    public function sendWelcomeEmail(User $user): void
    {
        try {
            SendEmailJob::dispatch(
                $user->email,
                new WelcomeMail($user)
            );
            Log::info("Email de bienvenue mis en queue pour {$user->email}");
        } catch (\Exception $e) {
            Log::error("Erreur mise en queue email bienvenue: " . $e->getMessage());
        }
    }

    /**
     * Envoyer confirmation de rendez-vous
     */
    public function sendAppointmentConfirmation(RendezVous $rendezVous): void
    {
        try {
            // Email au client
            SendEmailJob::dispatch(
                $rendezVous->user->email,
                new AppointmentConfirmation($rendezVous)
            );

            // Email au prestataire
            SendEmailJob::dispatch(
                $rendezVous->service->user->email,
                new AppointmentConfirmation($rendezVous)
            );

            Log::info("Emails confirmation RDV #{$rendezVous->id} mis en queue");
        } catch (\Exception $e) {
            Log::error("Erreur mise en queue email RDV: " . $e->getMessage());
        }
    }

    /**
     * Envoyer notification changement statut
     */
    public function sendAppointmentStatusChanged(RendezVous $rendezVous, string $oldStatus): void
    {
        try {
            SendEmailJob::dispatch(
                $rendezVous->user->email,
                new AppointmentStatusChanged($rendezVous, $oldStatus)
            );
            Log::info("Email changement statut RDV #{$rendezVous->id} mis en queue");
        } catch (\Exception $e) {
            Log::error("Erreur mise en queue email statut: " . $e->getMessage());
        }
    }

    /**
     * Envoyer email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail(string $email, string $resetUrl, string $userName): void
    {
        try {
            SendEmailJob::dispatch(
                $email,
                new ResetPasswordMail($resetUrl, $userName)
            );
            Log::info("Email réinitialisation mot de passe mis en queue pour {$email}");
        } catch (\Exception $e) {
            Log::error("Erreur mise en queue email reset: " . $e->getMessage());
        }
    }

    /**
     * Envoyer message de contact
     */
    public function sendContactEmail(array $data): void
    {
        try {
            $adminEmail = config('mail.from.address');

            SendEmailJob::dispatch(
                $adminEmail,
                new ContactMail($data)
            );
            Log::info("Email de contact mis en queue de {$data['email']}");
        } catch (\Exception $e) {
            Log::error("Erreur mise en queue email contact: " . $e->getMessage());
        }
    }
}

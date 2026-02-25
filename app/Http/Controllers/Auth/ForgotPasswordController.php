<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Afficher le formulaire de demande de réinitialisation
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation par email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success',
                'Un lien de réinitialisation a été envoyé à votre adresse email. Vérifiez votre boîte de réception (et vos spams).'
            );
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $this->translateStatus($status)]);
    }

    /**
     * Traduire les statuts en français
     */
    private function translateStatus(string $status): string
    {
        $translations = [
            Password::INVALID_USER => 'Aucun compte n\'est associé à cette adresse email.',
            Password::RESET_THROTTLED => 'Veuillez patienter avant de réessayer.',
        ];

        return $translations[$status] ?? 'Une erreur est survenue. Veuillez réessayer.';
    }
}

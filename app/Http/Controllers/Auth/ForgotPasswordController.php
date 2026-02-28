<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Un lien de réinitialisation a été envoyé à votre adresse email ! 📧'])
            : back()->withErrors(['email' => $this->getErrorMessage($status)]);
    }

    private function getErrorMessage(string $status): string
    {
        $messages = [
            Password::INVALID_USER => 'Aucun compte n\'est associé à cette adresse email.',
            Password::RESET_THROTTLED => 'Veuillez patienter avant de réessayer.',
        ];

        return $messages[$status] ?? 'Une erreur est survenue. Veuillez réessayer.';
    }
}

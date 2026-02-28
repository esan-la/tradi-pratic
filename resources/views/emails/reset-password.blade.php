{{-- resources/views/emails/reset-password.blade.php --}}
<x-mail::message>
# Réinitialisation de mot de passe 🔐

Bonjour **{{ $userName }}**,

Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte TradiPratic.

<x-mail::button :url="$resetUrl" color="primary">
Réinitialiser mon mot de passe
</x-mail::button>

<x-mail::panel>
⏰ Ce lien de réinitialisation expirera dans **{{ $expiresInMinutes }} minutes**.
</x-mail::panel>

Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise de votre part. Votre mot de passe actuel reste inchangé.

---

**🔒 Conseils de sécurité :**
- Ne partagez jamais votre mot de passe
- Utilisez un mot de passe unique et fort
- Activez la validation en 2 étapes si disponible

---

*Si le bouton ne fonctionne pas, copiez et collez le lien suivant dans votre navigateur :*
{{ $resetUrl }}

Cordialement,<br>
L'équipe **{{ config('app.name') }}**
</x-mail::message>

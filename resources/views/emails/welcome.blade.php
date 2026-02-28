{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}


{{-- resources/views/emails/welcome.blade.php --}}
<x-mail::message>
# Bienvenue sur TradiPratic, {{ $prenom }} {{ $nom }} ! 🌿

Nous sommes ravis de vous accueillir sur notre plateforme dédiée à la valorisation des pratiques traditionnelles.

Avec TradiPratic, vous pouvez :
- 🌾 **Découvrir** des pratiques agricoles traditionnelles
- 🍲 **Explorer** des recettes culinaires authentiques
- 🎨 **Admirer** des réalisations artisanales
- 📅 **Réserver** des services et rendez-vous

<x-mail::button :url="$loginUrl" color="success">
Accéder à mon compte
</x-mail::button>

Merci de faire partie de notre communauté !

Cordialement,<br>
L'équipe **{{ config('app.name') }}**
</x-mail::message>

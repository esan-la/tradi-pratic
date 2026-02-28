{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}


{{-- resources/views/emails/appointment-confirmation.blade.php --}}
<x-mail::message>
# Rendez-vous enregistré ! 📅

Bonjour **{{ $rendezVous->user->name }}**,

Votre rendez-vous a été enregistré avec succès.

<x-mail::panel>
🛠️ **Service :** {{ $service->titre }}

👤 **Prestataire :** {{ $prestataire->name }}

📅 **Date :** {{ \Carbon\Carbon::parse($rendezVous->date_rdv)->format('d/m/Y') }}

🕐 **Heure :** {{ \Carbon\Carbon::parse($rendezVous->heure_rdv)->format('H:i') }}

📍 **Lieu :** {{ $rendezVous->lieu ?? 'À définir' }}

📌 **Statut :** {{ ucfirst($rendezVous->statut) }}
</x-mail::panel>

@if($rendezVous->notes)
> **Notes :** {{ $rendezVous->notes }}
@endif

Vous recevrez une notification lorsque le prestataire confirmera votre rendez-vous.

<x-mail::button :url="route('public.rendez-vous.index')" color="primary">
Voir mes rendez-vous
</x-mail::button>

Cordialement,<br>
L'équipe **{{ config('app.name') }}**
</x-mail::message>

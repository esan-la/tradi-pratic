{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}


{{-- resources/views/emails/appointment-status.blade.php --}}
<x-mail::message>
# Mise à jour de votre rendez-vous 🔔

Bonjour **{{ $rendezVous->user->name }}**,

Le statut de votre rendez-vous a été modifié.

<x-mail::table>
| Détail | Information |
|:-------|:-----------|
| **Service** | {{ $rendezVous->service->titre }} |
| **Date** | {{ \Carbon\Carbon::parse($rendezVous->date_rdv)->format('d/m/Y à H:i') }} |
| **Ancien statut** | {{ ucfirst($oldStatus) }} |
| **Nouveau statut** | **{{ ucfirst($newStatus) }}** |
</x-mail::table>

@if($newStatus === 'confirme')
✅ **Bonne nouvelle !** Votre rendez-vous est confirmé. Nous vous attendons !
@elseif($newStatus === 'annule')
❌ Votre rendez-vous a été annulé. N'hésitez pas à en planifier un nouveau.
@elseif($newStatus === 'reporte')
🔄 Votre rendez-vous a été reporté. Vous serez informé de la nouvelle date.
@elseif($newStatus === 'termine')
✔️ Votre rendez-vous est terminé. Merci de votre confiance !
@endif

<x-mail::button :url="route('public.rendez-vous.index')">
Voir mes rendez-vous
</x-mail::button>

Cordialement,<br>
L'équipe **{{ config('app.name') }}**
</x-mail::message>

{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}

{{-- resources/views/emails/contact.blade.php --}}
<x-mail::message>
# Nouveau message de contact 📩

Un visiteur vous a envoyé un message depuis TradiPratic.

<x-mail::panel>
**De :** {{ $contactName }}

**Email :** {{ $contactEmail }}

**Sujet :** {{ $contactSubject }}
</x-mail::panel>

### Message :
> {{ $contactMessage }}

<x-mail::button :url="'mailto:' . $contactEmail">
Répondre à {{ $contactName }}
</x-mail::button>

---
*Ce message a été envoyé depuis le formulaire de contact de {{ config('app.name') }}.*
</x-mail::message>

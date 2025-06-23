@component('mail::message')
# Merci pour votre candidature

Bonjour {{ $candidature->candidat->nom }},

Nous avons bien reçu votre candidature pour l’offre **{{ $candidature->offre->titre }}**.

Votre identifiant de suivi est :

@component('mail::panel')
**{{ $candidature->uuid }}**
@endcomponent

Vous pouvez consulter l’état de votre candidature à tout moment via ce lien :

@component('mail::button', ['url' => route('candidatures.suivi', $candidature->uuid)])
Suivre ma candidature
@endcomponent

Merci pour votre confiance.

Cordialement,
L’équipe RH
@endcomponent

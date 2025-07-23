@component('mail::message')
# Bonjour {{ $candidat->nom }},

Votre candidature a été validé avec succès.

**Détails du stage :**

- Sujet : {{ $stage->sujet }}
- Date de début : {{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}
- Date de fin : {{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}
- Lieu : {{ $stage->lieu }}

Merci de rester attentif à vos prochaines communications.

Cordialement,
L'équipe RH
@endcomponent

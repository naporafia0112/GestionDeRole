@php
    $urlRetour = match($retour) {
        'retenus' => route('candidatures.retenus'),
        'cours' => url()->previous(),
        default => route('candidatures.index'),
    };
@endphp

<a href="{{ $urlRetour }}" class="btn btn-sm me-1 btn-link">
    <i class="mdi mdi-keyboard-backspace"></i> Retour
</a>

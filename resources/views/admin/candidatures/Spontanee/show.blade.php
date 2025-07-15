@extends('layouts.home')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container mt-4">
    <!-- Titre -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title"><strong>Détails de la candidature spontanée</strong></h4>
                </div>
                <div>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.candidatures.spontanees.index') }}">Candidatures spontanées</a></li>
                        <li class="breadcrumb-item active">Détail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="row mt-3">
        <!-- Colonne gauche -->
        <div class="{{ !$candidature->cv_fichier && !$candidature->lm_fichier && !$candidature->lr_fichier ? 'col-lg-12' : 'col-lg-8' }}">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.candidatures.spontanees.index') }}" class="btn btn-sm btn-link mb-3">
                        <i class="mdi mdi-keyboard-backspace"></i> Retour
                    </a>

                    <h5 class="mb-4">
                        Candidature n°{{ str_pad($numero, 3, '0', STR_PAD_LEFT) }}
                        <small class="text-muted ms-2">(ID: {{ $candidature->id }})</small>
                    </h5>

                    <!-- Infos candidat -->
                    <h4 class="mb-3">Informations du candidat</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Nom :</strong></label>
                            <p>{{ $candidature->candidat->nom }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Prénoms :</strong></label>
                            <p>{{ $candidature->candidat->prenoms }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Type de dépôt :</strong></label>
                            <p>{{ $candidature->candidat->type_depot }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Email :</strong></label>
                            <p>{{ $candidature->candidat->email }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Téléphone :</strong></label>
                            <p>{{ $candidature->candidat->telephone ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label><strong>Ville / Quartier :</strong></label>
                            <p>{{ $candidature->candidat->ville }} / {{ $candidature->candidat->quartier }}</p>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label><strong>Statut :</strong></label>
                            @if($statut === 'en_cours')
                                <span class="badge bg-warning text-dark">En cours</span>
                            @elseif($statut === 'retenu')
                                <span class="badge bg-success">Retenu</span>
                            @elseif($statut === 'rejete')
                                <span class="badge bg-danger">Rejeté</span>
                            @else
                                <span class="badge bg-secondary">Inconnu</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label><strong>Date de dépôt :</strong></label>
                            <p>{{ $candidature->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Message -->
                    @if($candidature->message)
                    <div class="mt-3">
                        <label><strong>Message du candidat :</strong></label>
                        <div class="p-2 bg-light border rounded">
                            {{ $candidature->message }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite : Fichiers -->
        @if($candidature->cv_fichier || $candidature->lm_fichier || $candidature->lr_fichier)
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><strong>Fichiers joints</strong></h5>

                    @foreach([
                        'cv_fichier' => 'CV',
                        'lm_fichier' => 'Lettre de motivation',
                        'lr_fichier' => 'Lettre de recommandation'
                    ] as $champ => $label)
                        @if($candidature->$champ && Storage::disk('public')->exists($candidature->$champ))
                            <div class="mb-4">
                                <label class="form-label"><strong>{{ $label }}</strong></label>

                                <!-- Aperçu PDF -->
                                <embed src="{{ route('candidatures.preview', ['id' => $candidature->id, 'field' => $champ]) }}"
                                       type="application/pdf"
                                       width="100%" height="250"
                                       class="border rounded" />

                                <!-- Boutons -->
                                <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'field' => $champ]) }}"
                                   class="btn btn-sm btn-outline-primary w-100 mt-2">
                                    <i class="mdi mdi-download"></i> Télécharger {{ strtolower($label) }}
                                </a>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.home')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container mt-4">
    <!-- Titre et breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">
                        <strong>Détails de la candidature spontanée</strong>
                    </h4>
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

    <!-- Contenu principal -->
    <div class="row mt-3">
        <!-- Colonne gauche -->
        <div class="{{ !$candidature->cv_fichier && !$candidature->lm_fichier && !$candidature->lr_fichier ? 'col-lg-12' : 'col-lg-8' }}">
            <div class="card d-block h-100">
                <div class="card-body">
                    <!-- Numéro -->
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

                    <hr class="my-4">

                    <!-- Date -->
                    <div class="text-muted mt-3">
                        <i class="mdi mdi-calendar-clock"></i>
                        Déposée le : <strong>{{ $candidature->created_at->format('d/m/Y H:i') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : fichiers -->
        @if($candidature->cv_fichier || $candidature->lm_fichier || $candidature->lr_fichier)
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><strong>Fichiers joints</strong></h5>

                    @foreach ([
                        'cv_fichier' => 'CV',
                        'lm_fichier' => 'Lettre de motivation',
                        'lr_fichier' => 'Lettre de recommandation'
                    ] as $champ => $label)
                        @if($candidature->$champ && Storage::disk('public')->exists($candidature->$champ))
                            <div class="mb-4">

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

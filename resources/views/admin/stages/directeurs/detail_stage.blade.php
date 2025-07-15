@extends('layouts.home')
@section('content')
<div class="container mt-4">
     <div class="card d-block">
        <div class="card-body">
    <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{route('stages.en_cours')}}">Stages en cours</a></li>
                                <li class="breadcrumb-item active"><strong>Détails du stage</strong></li>
                            </ol>
                        </div>
                        <h4 class="page-title"><strong>Détails du stage N°{{ $numero }}</strong></h4>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('stages.en_cours') }}" class="btn btn-sm btn-link"><i class="mdi mdi-keyboard-backspace"></i>Retour</a>
                    </div>
                </div>
            </div>
    <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="float-sm-end mb-2 mb-sm-0">
                        <div class="row g-2">
                            <div class="col-auto">
                            </div>

                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Colonne gauche - Informations principales -->
                        <div class="col-md-6">
                            <h5 class="mb-3"><strong>Informations du stage</strong></h5>

                            <div class="mb-3">
                                <label class="mb-1"><strong>Sujet :</strong></label>
                                <p class="p-2 bg-light rounded">{{ $stage->sujet }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="mb-1"><strong>Dates :</strong></label>
                                <p>
                                    <i class="mdi mdi-calendar-range text-primary me-1"></i>
                                    Du <strong>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') }}</strong>
                                    au <strong>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') }}</strong>
                                    ({{ \Carbon\Carbon::parse($stage->date_debut)->diffInDays($stage->date_fin) }} jours)
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="mb-1"><strong>Lieu :</strong></label>
                                <p>
                                    <i class="mdi mdi-map-marker text-danger me-1"></i>
                                    <strong>{{ $stage->lieu }}</strong>
                                </p>
                            </div>

                           <div class="mb-3">
                                <label class="mb-1"><strong>Département :</strong></label>
                                @if($departement)
                                    <p class="p-2 bg-light rounded">{{ $departement->nom }}</p>
                                @else
                                    <p class="text-muted">Département non spécifié</p>
                                @endif
                            </div>
                                                    </div>

                            <!-- Colonne droite - Candidat et Tuteur -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><strong>Participants</strong></h5>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title"><strong>Candidat</strong></h6>
                                    @if($candidat)
                                        <p class="mb-1">
                                            <i class="mdi mdi-account-outline me-1"></i>
                                            {{ $candidat->nom }} {{ $candidat->prenoms }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="mdi mdi-email-outline me-1"></i>
                                            {{ $candidat->email ?? 'Non renseigné' }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="mdi mdi-phone-outline me-1"></i>
                                            {{ $candidat->telephone ?? 'Non renseigné' }}
                                        </p>
                                    @else
                                        <div class="alert alert-warning p-2 mb-0">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                            Aucun candidat associé à ce stage
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title"><strong>Tuteur</strong></h6>
                                    @if($stage->tuteur)
                                        <p class="mb-1">
                                            <i class="mdi mdi-account-tie-outline me-1"></i>
                                            {{ $stage->tuteur->name }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="mdi mdi-email-outline me-1"></i>
                                            {{ $stage->tuteur->email }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="mdi mdi-phone-outline me-1"></i>
                                            {{ $stage->tuteur->telephone ?? 'Non renseigné' }}
                                        </p>
                                    @else
                                        <div class="alert alert-warning p-2 mb-0">
                                            <i class="mdi mdi-alert-circle-outline me-1"></i>
                                            Aucun tuteur affecté
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h5 class="mb-3"><strong>Informations  complémentaires</strong></h5>
                            <div class="mb-3">
                                <label class="mb-1"><strong>Statut :</strong></label>
                                <p>
                                    @if($stage->statut === 'en_attente')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @elseif($stage->statut === 'en_cours')
                                        <span class="badge bg-success">En cours</span>
                                    @elseif($stage->statut === 'termine')
                                        <span class="badge bg-secondary">Terminé</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="mb-1"><strong>Rémunération :</strong></label>
                                <p>
                                    @if($stage->remuneration)
                                        <strong>{{ number_format($stage->remuneration, 2, ',', ' ') }} FCFA</strong>
                                    @else
                                        <span class="text-muted">Non spécifiée</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

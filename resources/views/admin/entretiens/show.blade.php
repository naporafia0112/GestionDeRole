@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('entretiens.index') }}">Entretiens</a></li>
                        <li class="breadcrumb-item active">Détails de l'entretien</li>
                    </ol>
                </div>
                <h4 class="page-title"><strong>Entretien du {{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</strong></h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card d-block">
                <div class="card-body">
                    <div class="float-end">
                        <a href="{{ route('entretiens.index') }}" class="btn btn-sm btn-link">
                            <i class="mdi mdi-keyboard-backspace"></i> Retour
                        </a>
                    </div>

                    <h4 class="mb-3"><strong>Détails de l'entretien</strong></h4>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Date :</strong></label>
                            <p>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Heure :</strong></label>
                            <p>{{ \Carbon\Carbon::parse($entretien->heure)->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Lieu :</strong></label>
                            <p>{{ $entretien->lieu }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Statut :</strong></label>
                            @if($entretien->statut === 'programme')
                                <span class="badge bg-warning text-dark">Programmé</span>
                            @elseif($entretien->statut === 'effectuee')
                                <span class="badge bg-success">Effectué</span>
                            @else
                                <span class="badge bg-secondary">Inconnu</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h4 class="mb-3 mt-4"><strong>Informations sur le candidat</strong></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Nom :</strong></label>
                            <p>{{ $entretien->candidat->nom }} {{ $entretien->candidat->prenoms }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Email :</strong></label>
                            <p>{{ $entretien->candidat->email ?? '-' }}</p>
                        </div>
                    </div>

                    <h4 class="mb-3 mt-4"><strong>Offre concernée</strong></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Titre de l'offre :</strong></label>
                            <p>{{ $entretien->offre->titre }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Département :</strong></label>
                            <p>{{ $entretien->offre->departement ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Date limite :</strong></label>
                            <p>{{ $entretien->offre->date_limite?->format('d/m/Y') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Statut de l'offre :</strong></label>
                            <span class="badge bg-{{ $entretien->offre->statut === 'publie' ? 'success' : 'secondary' }}">
                                {{ ucfirst($entretien->offre->statut) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('offres.show', $entretien->offre->id) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-eye"></i> Voir l'offre
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

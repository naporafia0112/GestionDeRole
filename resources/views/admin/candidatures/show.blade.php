@extends('layouts.home')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container mt-4">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offres.candidatures',$candidature->offre->id) }}">Candidatures</a></li>
                        <li class="breadcrumb-item active"><strong>Détails de la candidature</strong></li>
                    </ol>
                </div>
<h4 class="page-title">
    <strong>
        Candidature N°{{ str_pad($numero, 3, '0', STR_PAD_LEFT) }} <small class="text-muted ms-2">(ID: {{ $candidature->id }})</small>
    </strong>
</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Colonne gauche - Infos candidat et offre -->
        <div class="{{ !$candidature->cv_fichier && !$candidature->lm_fichier && !$candidature->lr_fichier ? 'col-lg-12' : 'col-lg-8' }}">
            <div class="card d-block h-100">
                <div class="card-body">

                    <div class="float-sm-end mb-2">
                        <div class="btn-group">
                            <a href="{{ route('offres.candidatures',$candidature->offre->id) }}" class="btn btn-sm me-1 btn-link">
                                <i class="mdi mdi-keyboard-backspace"></i> Retour
                            </a>

                            @if(!in_array($candidature->statut, ['rejete', 'retenu']))
                            <form action="{{ route('candidatures.reject', $candidature->id) }}" id="rejeter-candidature-{{ $candidature->id }}" method="POST" onsubmit="return confirm('Confirmer le rejet de cette candidature ?');">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-sm me-1 btn-outline-danger" onclick="confirmRejet({{ $candidature->id}})" title="Rejeter">
                                <i class="mdi mdi-close-circle-outline"></i>Rejeter
                            </button>
                            </form>
                            @endif
                        </div>
                    </div>


                    <!-- Infos candidat -->
                    <h4 class="mb-3 mt-0 font-18"><strong>Informations sur le candidat</strong></h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Nom :</strong></label>
                            <p><strong>{{ $candidature->candidat->nom }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Prénoms :</strong></label>
                            <p><strong>{{ $candidature->candidat->prenoms }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Type de dépôt :</strong></label>
                            <p><strong>{{ $candidature->candidat->type_depot ?? '-' }}</strong></p>
                        </div>
                    </div>


                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Email :</strong></label>
                            <p><strong>{{ $candidature->candidat->email }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Téléphone :</strong></label>
                            <p><strong>{{ $candidature->candidat->telephone ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Ville :</strong></label>
                            <p><strong>{{ $candidature->candidat->ville ?? '-' }}</strong></p>
                        </div>

                    </div>

                    <!-- Statut -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Statut de la candidature :</strong></label>
                            @if($candidature->statut === 'en_cours')
                            <span class="badge bg-warning text-dark">En cours de traitement</span>
                            @elseif($candidature->statut === 'retenu')
                            <span class="badge bg-success">Retenu</span>
                            @elseif($candidature->statut === 'rejete')
                            <span class="badge bg-danger">Rejeté</span>
                            @else
                            <span class="badge bg-secondary">Inconnu</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Quartier :</strong></label>
                            <p><strong>{{ $candidature->candidat->quartier ?? '-' }}</strong></p>
                        </div>
                         <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Entretients :</strong></label>
                            <p><strong></strong></p>
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- Infos de l'offre associée -->
                    <h4 class="mb-3 mt-0 font-18 text-primary"><strong>Offre associée</strong></h4>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Localisation :</strong></label>
                            <p>
                                <i class="mdi mdi-map-marker text-danger me-1"></i>
                                <strong>{{ $candidature->offre->localisation->pays ?? '-' }}</strong>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Statut :</strong></label>
                            <span class="badge bg-{{ $candidature->offre->statut == 'publie' ? 'success' : 'warning' }}">
                                <strong>{{ ucfirst($candidature->offre->statut) }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Date de publication :</strong></label>
                            <p><strong>{{ $candidature->offre->date_publication?->format('d/m/Y') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Date limite :</strong></label>
                            <p><strong>{{ $candidature->offre->date_limite?->format('d/m/Y') ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <label class="mt-3 mb-1"><strong>Département :</strong></label>
                    <div class="p-2 rounded mb-3">
                    <p><strong>{{ $candidature->offre->departement ?? '-' }}</strong></p>
                    </div>
                    <label class="mt-3 mb-1"><strong>Description :</strong></label>
                    <div class=" p-2 rounded mb-3">
                        {!! nl2br(e($candidature->offre->description ?? '-')) !!}
                    </div>

                    <label class="mt-3 mb-1"><strong>Exigences :</strong></label>
                    <div class=" p-3 rounded">
                        {!! nl2br(e($candidature->offre->exigences ?? '-')) !!}
                    </div>
                </div>
            </div>
        </div>

    @if($candidature->cv_fichier || $candidature->lm_fichier || $candidature->lr_fichier)
        <!-- Colonne droite - Fichiers PDF -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><strong>Fichiers joints</strong></h5>

                    @foreach (['cv_fichier' => 'CV', 'lm_fichier' => 'Lettre de motivation', 'lr_fichier' => 'Lettre de recommandation'] as $champ => $label)
                        @if($candidature->$champ && Storage::disk('public')->exists($candidature->$champ))
                            <div class="mb-4">
                                <label class="form-label"><strong>{{ $label }}</strong></label>
                                <embed src="{{ route('candidatures.preview', ['id' => $candidature->id, 'field' => $champ]) }}" type="application/pdf" width="100%" height="200px" class="border rounded">
                                <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'field' => $champ]) }}"
                                   class="btn btn-outline-{{ $champ == 'cv_fichier' ? 'primary' : ($champ == 'lm_fichier' ? 'success' : 'warning') }} mt-2 w-100">
                                    <i class="dripicons-download"></i> Télécharger {{ strtolower($label) }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

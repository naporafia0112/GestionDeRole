@extends('layouts.vitrine.vitrine')
@php
    use Illuminate\Support\Facades\Storage;
    $hasFile = $offre->fichier && Storage::disk('public')->exists($offre->fichier);
@endphp

@section('content')
<div class="container mt-4">
    <!-- start page title -->
    <!-- end page title -->

    <div class="row">
        <!-- Colonne gauche - Carte Principale -->
        <div class="{{ $hasFile ? 'col-lg-8' : 'col-12' }}">
            <div class="card d-block h-100">
                <div class="card-body">
                    <div class="float-sm-end mb-2 mb-sm-0">
                       <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <a href="{{ route('vitrine.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-arrow-left me-2"></i> Retour
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('candidature.create', $offre->id) }}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-pencil"></i> Postuler à l'offre
                                </a>
                            </div>
                        </div>

                    </div>

                    <h4 class="mb-3 mt-0 font-18"><strong>{{ $offre->titre }}</strong></h4>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Localisation :</strong></label>
                            <p>
                                <i class="mdi mdi-map-marker text-danger me-1"></i>
                                <strong>{{ $offre->localisation->pays }}</strong>
                            </p>
                        </div>
                        <!--div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Département :</strong></label>
                            <p><strong>{{--  --}}</strong></p>
                        </!--div-->
                        <!--div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Statut :</strong></label>
                                @if($offre->est_publie)
                                <span class="badge bg-success">Publié</span>
                                @else
                                <span class="badge bg-secondary">Brouillon</span>
                                @endif
                            </span>
                        </div-->
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Date de publication :</strong></label>
                            <p><strong>{{ $offre->date_publication->format('d/m/Y') }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Date limite :</strong></label>
                            <p><strong>{{ $offre->date_limite->format('d/m/Y') }}</strong></p>
                        </div>
                    </div>

                    <label class="mt-3 mb-1"><strong>Description :</strong></label>
                    <div class=" p-2 rounded mb-3">
                        {!! nl2br(e($offre->description)) !!}
                    </div>

                    <label class="mt-3 mb-1"><strong>Exigences :</strong></label>
                    <div class=" p-3 rounded">
                        {!! nl2br(e($offre->exigences)) !!}
                    </div>
                </div>
            </div>
        </div>

        @if($hasFile)
        <!-- Colonne droite - PDF -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><strong>Fiche de poste</strong></h5>
                    <div class="text-center mb-3">
                        <embed src="{{ asset('storage/'.$offre->fichier) }}"
                               type="application/pdf"
                               width="100%" height="300px">
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ asset('storage/'.$offre->fichier) }}"
                           class="btn btn-primary" download>
                            <i class="dripicons-download me-1"></i>
                            <strong>Télécharger le PDF</strong>
                            ({{ round(Storage::disk('public')->size($offre->fichier) / 1024) }} KB)
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.vitrine.vitrine')

@section('content')
<div class="col-auto">

</div>
<section class="section light-background">
  <div class="container">
    <a href="{{ route('vitrine.index') }}" class="text-white fs-2">
            <i class="bi bi-arrow-left"></i>
            </a>
    <div class="row">

      {{-- Colonne gauche : PDF --}}
      <div class="col-lg-3 mb-2">
        @if($offre->fichier && Storage::disk('public')->exists($offre->fichier))
          <div class="card h-100" style="border: 3px solid #29a9d4; height: 50%;">
            <div class="card-body">
              <h5 class="card-title"><strong>Fiche de poste</strong></h5>
              <embed src="{{ asset('storage/'.$offre->fichier) }}"
                     type="application/pdf"
                     style="width: 100%; height: 400px; border-radius: 4px; border: 1px solid #ddd;" />
              <div class="d-grid gap-2 mt-3">
                <a href="{{ asset('storage/'.$offre->fichier) }}"
                   class="btn btn-secondary"
                   download>
                   <i class="dripicons-download me-1"></i>
                   <strong>Télécharger le PDF</strong>
                   ({{ round(Storage::disk('public')->size($offre->fichier) / 1024) }} KB)
                </a>
              </div>
            </div>
          </div>
        @endif
      </div>

      {{-- Colonne droite : contenu de l’offre --}}
      <div class="col-lg-9 ">
        <div class="card " style="border: 3px solid #29a9d4;">
          <div class="card-body">

            <h2 class="mb-3">{{ $offre->titre }}</h2>
            <p><strong>Département :</strong> {{ $offre->departement }}</p>
            <p><strong>Lieu :</strong> {{ $offre->localisation->pays ?? 'Non précisé' }}</p>
            <p><strong>Date limite :</strong> {{ $offre->date_limite->format('d/m/Y') }}</p>

            <div class="mt-4" style="border: 1px solid #c0ebf9;">
              <h4>Description de l'offre</h4>
              <p style="white-space: pre-line;">{!! nl2br(e($offre->description)) !!}</p>
            </div>

            <div class="mt-4">
              <h4>Exigences</h4>
              <p style="white-space: pre-line;">{!! nl2br(e($offre->exigences)) !!}</p>
            </div>

            <div class="mt-6" id="hero">
              <a href="{{ route( 'candidature.create', $offre->id) }}" class=" btn btn-secondary">Postuler</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

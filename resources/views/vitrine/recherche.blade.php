@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Résultats Section -->
<section id="resultats" class="results-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8" data-aos="fade-up">

        <!-- Carte de résultat -->
        <div class="result-card bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4">
          <div class="text-center mb-4">
            <i class="bi bi-file-earmark-person display-4 text-primary mb-3"></i>
            <h2 class="h3">Résultat de votre recherche</h2>
          </div>

         @if(isset($message))
        <div class="d-flex align-items-center p-3 rounded" style="background-color: #e7f8ff; border-left: 4px solid #4fc3f7;">
        <i class="bi bi-info-circle-fill me-3" style="color: #4fc3f7; font-size: 1.2rem;"></i>
        <div>
            <p class="mb-0" style="color: #00688b;">{!! $message !!}</p>
        </div>
        </div>
        @endif

          @if(isset($candidature))
          <div class="result-content">
            <div class="row">
              <div class="col-md-4 text-center mb-4 mb-md-0">
                <div class="avatar-placeholder rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                     style="width: 120px; height: 120px;">
                  <i class="bi bi-person text-muted" style="font-size: 3rem;"></i>
                </div>
              </div>
              <div class="col-md-8">
                <h3 class="h4">{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</h3>
                <p class="text-muted mb-3">Candidature #{{ $candidature->uuid }}</p>

                <div class="d-flex align-items-center mb-3">
                  <div class="me-3">
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
                  <small class="text-muted">Mise à jour: {{ $candidature->updated_at->format('d/m/Y H:i') }}</small>
                </div>

                <div class="candidature-details">
                  <div class="row">
                    <div class="col-sm-6 mb-3">
                      <h4 class="h6 text-muted mb-1">Poste</h4>
                      <p>{{ $candidature->offre->titre ?? 'Non spécifié' }}</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                      <h4 class="h6 text-muted mb-1">Date de candidature</h4>
                      <p>{{ $candidature->created_at->format('d/m/Y') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          <div class="text-center mt-4">
            <a href="{{ route('vitrine.index') }}" class="btn btn-outline-primary rounded-pill px-4">
              <i class="bi bi-arrow-left me-2"></i> Retour à l'accueil
            </a>
          </div>
        </div>

        <!-- Section d'aide -->
        <div class="help-card bg-light rounded-4 p-4 mt-4">
          <h3 class="h5 mb-3"><i class="bi bi-question-circle me-2"></i>Besoin d'aide ?</h3>
          <p class="mb-2">Si vous rencontrez un problème avec votre candidature, contactez notre service support :</p>
          <ul class="list-unstyled">
            <li><i class="bi bi-envelope me-2"></i> cagecfi@cagecfi.com</li>
            <li><i class="bi bi-telephone me-2"></i>+228 22 26 84 61</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

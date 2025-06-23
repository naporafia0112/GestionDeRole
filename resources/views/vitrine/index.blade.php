@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Offres Publiées Section -->

<!-- Hero Section avec fond animé -->
<section id="hero" class="hero section light-background position-relative">
  <!-- Fond animé -->
  <div class="background-slider">
    <div class="slider-track">
      <img src="{{ asset('assets-vitrine/img/fond3.jpg') }}" alt="fond1">
      <img src="{{ asset('assets-vitrine/img/fond4.jpg') }}" alt="fond2">
      <img src="{{ asset('assets-vitrine/img/fond3.jpg') }}" alt="fond1">
      <img src="{{ asset('assets-vitrine/img/fond4.jpg') }}" alt="fond2">
    </div>
  </div>

  <!-- Contenu -->
  <div class="container position-relative z-2" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-white">
        <h2></h2>
        <p>Découvrez nos opportunités de stage. Trouvez celle qui correspond à votre profil.</p>
        <div class="d-flex mt-0">
            <form action="{{ route('candidatures.recherche') }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="uuid" class="form-control" placeholder="Saisissez l'UUID" value="{{ old('uuid') }}">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </form>
            @if(isset($message))
                <div class="alert alert-warning mt-2">{{ $message }}</div>
            @endif
            @if(isset($candidature))
                <div class="card mt-2">
                    <div class="card-body">
                        <h5>Candidature trouvée :</h5>
                        <p><strong>Nom :</strong> {{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</p>
                        <p>
                        <strong>Statut :</strong>
                        @if($candidature->statut === 'en cours de traitement')
                            <span class="badge bg-primary">En cours de traitement</span>
                        @elseif($candidature->statut === 'acceptée')
                            <span class="badge bg-success">Acceptée</span>
                        @elseif($candidature->statut === 'refusée')
                            <span class="badge bg-danger">Refusée</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($candidature->statut) }}</span>
                        @endif
                        </p>
                        <a href="{{ route('vitrine.index') }}" class="btn btn-outline-secondary btn-sm mt-1">Annuler</a>
                        <!-- Ajoute ici d'autres infos utiles -->
                    </div>
                </div>
            @endif
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Offres -->
<section id="offres" class="services section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Nos Offres</h2>
    <div><span class="description-title">Consultez les offres disponibles</span></div>
        <p>Bienvenue sur notre plateforme dédiée aux offres de stages !
Ici, les étudiants et les jeunes diplômés peuvent trouver des opportunités enrichissantes dans divers domaines, proposées par des entreprises partenaires engagées.
Notre objectif est de faciliter la rencontre entre les talents en formation et les structures à la recherche de profils motivés.
Parcourez les offres, postulez en quelques clics et suivez l’évolution de votre candidature en toute simplicité.
Nous vous souhaitons plein succès dans votre recherche de stage !</p>
  </div>

  <div class="container">
    <div class="row gy-4">
      @forelse($offres as $offre)
        <div class="col-lg-4 col-md-6" data-aos="fade-up">
          <div class="service-item position-relative offre-card">
            <div class="icon">
              <i class="bi bi-briefcase"></i>
            </div>
            <h3>{{ $offre->titre }}</h3>
            <p>{{ Str::limit($offre->description, 120) }}</p>
            <small><strong>Lieu:</strong> {{ $offre->localisation->pays ?? 'Non précisé' }}</small><br>
            <small><strong>Date limite:</strong> {{ $offre->date_limite->format('d/m/Y') }}</small>
            <a href="{{ route('vitrine.show', $offre->id) }}" class="stretched-link"></a>
          </div>
        </div>
      @empty
        <div class="col-12 text-center">
          <div class="alert alert-info">Aucune offre disponible pour le moment.</div>
        </div>
      @endforelse
    </div>
    <div class="d-flex justify-content-end mt-4">
    <a href="{{ route('vitrine.catalogue') }}" class="btn btn-outline-primary">
        Voir le catalogue complet
    </a>
    </div>
  </div>
</section>
@endsection

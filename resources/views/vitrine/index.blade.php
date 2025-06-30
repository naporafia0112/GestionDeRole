@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Offres Publiées Section -->

<!-- Hero Section avec fond animé -->
<!-- Hero Section avec fond animé - Version compacte -->
<section id="hero" class="hero section light-background position-relative">
  <div class="background-slider">
    <div class="slider-track">
      <img src="{{ asset('assets-vitrine/img/fond3.jpg') }}" alt="fond1">
      <img src="{{ asset('assets-vitrine/img/fond4.jpg') }}" alt="fond2">
    </div>
  </div>

  <div class="container position-relative z-2" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-white">
        <h2 class="mb-3">Suivi de candidature</h2>
        <p class="mb-4">Entrez votre identifiant unique pour suivre l'état de votre candidature</p>
        <div class="d-flex mt-0">
          <form action="{{ route('candidatures.recherche') }}" method="POST" class="w-100">
            @csrf
            <div class="input-group input-group-lg" style="max-width: 500px;"> <!-- Limite la largeur max -->
              <input type="text" name="uuid" class="form-control rounded-pill-start"
                     placeholder="Votre UUID (ex: ABC123)" value="{{ old('uuid') }}"
                     style="height: 44px; font-size: 0.9rem;"> <!-- Taille réduite -->
              <button type="submit" class="btn btn-primary rounded-pill-end px-3"
                      style="height: 44px; font-size: 0.9rem;"> <!-- Taille réduite -->
                <i class="bi bi-search me-1"></i>Suivre <!-- Icône + texte compact -->
              </button>
            </div>
          </form>
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
<div class="container text-center" data-aos="fade-up" style="margin-top: 10px; margin-bottom: 25px;">
    <a href="{{ route('vitrine.catalogue') }}" class="btn btn-custom-blue px-4 py-2 rounded-pill shadow-sm">
        Voir le catalogue complet
    </a>
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

  </div>
</section>
@endsection

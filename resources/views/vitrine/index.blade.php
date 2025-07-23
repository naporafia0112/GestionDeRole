@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Offres Publiées Section -->

<!-- Hero Section avec fond animé -->
<!-- Hero Section avec fond animé - Version compacte -->
<section id="hero" class="hero section position-relative">
<!--img src="{{ asset('assets-vitrine/img/gif.gif') }}" alt="GIF animé" class="top-right-gif"-->
  <!-- GIF animé en fond -->
  <div class="animated-gif-background">
    <img src="{{ asset('assets-vitrine/img/fond7.jpg') }}" alt="Background animation" class="gif-bg">
    <div class="color-overlay"></div>
  </div>

  <div class="container position-relative z-2" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-white">
        <!-- Titre avec effet de vague -->
        <h2 class="mb-3 wave-text">Suivi de candidature</h2>

        <!-- Texte avec apparition caractère par caractère -->
        <p class="mb-4 typed-text">Entrez votre identifiant unique pour suivre l'état de votre candidature</p>

        <!-- Formulaire avec effet "morphing" -->
        <div class="d-flex mt-0">
          <form action="{{ route('candidatures.recherche') }}" method="POST" class="w-100 morphing-form">
            @csrf
            <div class="input-group input-group-lg" style="max-width: 500px;">
              <input type="text" name="uuid" class="form-control rounded-pill-start hover-float"
                     placeholder="Votre UUID (ex: ABC123)" value="{{ old('uuid') }}"
                     style="height: 44px; font-size: 0.9rem;">
              <button type="submit" class="btn btn-primary rounded-pill-end px-3 hover-grow"
                      style="height: 44px; font-size: 0.9rem;">
                <i class="bi bi-search me-1"></i>Suivre
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<div class="position-fixed end-0 bottom-0 p-4 z-3" style="transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);">
    <a href="{{ route('candidature.spontanee.form') }}" class="text-decoration-none">
        <div class="card bg-primary-gradient border-0 rounded-4 text-white shadow-lg hover-shadow-lg"
             style="transition: all 0.4s ease; backdrop-filter: blur(8px); background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 20px -8px rgba(0,0,0,0.2)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 16px -4px rgba(0,0,0,0.1)'">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-semibold">Pas d'offre correspondante ?</p>
                        <p class="mb-0 opacity-75">Postulez spontanément</p>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </a>
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

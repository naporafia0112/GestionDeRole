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
<div class="position-absolute end-0 bottom-0 p-4 z-3" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">>
    <a href="{{ route('candidature.spontanee.form') }}" class="mb-2 text-white">
        <div class="card bg-white bg-opacity-10 border rounded-4 text-white shadow-sm border-0 hover-shadow" style="transition: all 0.3s ease;">
            <div class="card-body">
                <p class="mb-2">Vous ne trouvez pas d’offre qui correspond à votre profil ?<br/>Faire un dépôt spontané</p>
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

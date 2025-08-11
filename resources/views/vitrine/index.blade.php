@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Hero Section avec fond animé -->
<section id="hero" class="hero section position-relative">
  <div class="animated-gif-background">
    <img src="{{ asset('assets-vitrine/img/final.png') }}" alt="Background animation" class="gif-bg">
    <div class="color-overlay"></div>
  </div>

  <div class="container position-relative z-2" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-white">
        <h2 class="mb-3 wave-text">Suivi de candidature</h2>
        <p class="mb-4 typed-text">Entrez votre identifiant unique pour suivre l'état de votre candidature</p>

        <div class="d-flex mt-1">
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

        <!-- Lien vers le popup -->
        <p class="text-white mt-3">
            Vous n'avez pas reçu d'email ?
            <a href="#" data-bs-toggle="modal" data-bs-target="#resendEmailModal" class="text-dark text-decoration-underline">Cliquez ici pour le renvoyer</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Lien candidature spontanée -->
  <div class="position-fixed end-0 bottom-0 p-4 z-3">
    <a href="{{ route('candidature.spontanee.form') }}" class="text-decoration-none">
      <div class="card bg-primary-gradient border-0 rounded-4 text-white shadow-lg hover-shadow-lg"
           style="transition: all 0.4s ease; backdrop-filter: blur(8px); background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);"
           onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 20px -8px rgba(0,0,0,0.2)'"
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 16px -4px rgba(0,0,0,0.1)'">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
            <div class="flex-grow-1">
              <p class="mb-0 fw-semibold">Pas d'offre correspondante ?</p>
              <p class="mb-0 opacity-75">Postulez spontanément</p>
            </div>
            <div class="flex-shrink-0 ms-2">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2"/>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
</section>

<!-- Section des Offres -->
<section id="offres" class="services section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Nos Offres</h2>
    <div><span class="description-title">Consultez les offres disponibles</span></div>
    <p>Bienvenue sur notre plateforme dédiée aux offres de stages ! Ici, les étudiants et les jeunes diplômés peuvent trouver des opportunités enrichissantes dans divers domaines, proposées par des entreprises partenaires engagées. Notre objectif est de faciliter la rencontre entre les talents en formation et les structures à la recherche de profils motivés. Parcourez les offres, postulez en quelques clics et suivez l’évolution de votre candidature en toute simplicité. Nous vous souhaitons plein succès dans votre recherche de stage !</p>
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
            <div class="icon"><i class="bi bi-briefcase"></i></div>
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

<!-- Modal de renvoi de mail -->
<div class="modal fade" id="resendEmailModal" tabindex="-1" aria-labelledby="resendEmailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="resendEmailModalLabel">Renvoyer l'email de confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <form method="POST" action="{{ route('candidature.renvoi.email') }}">
        @csrf
        <div class="modal-body">
          <p>Entrez votre adresse email pour recevoir à nouveau l'email de suivi de candidature.</p>
          <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="exemple@email.com"
                   value="{{ old('email') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Renvoyer l'email</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

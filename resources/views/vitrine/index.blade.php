
@extends('layouts.vitrine.vitrine')

@section('content')
<!-- Offres Publiées Section -->
<!-- Hero Section -->
<section id="hero" class="hero section light-background">
  <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
    <div class="row gy-5">
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
        <h2>DIPRH</h2>
        <p>Découvrez nos opportunités de stage. Trouvez celle qui correspond à votre profil.</p>
        <div class="d-flex mt-0">
          <a href="{{ route('vitrine.consulter')}}" class="btn-get-started bg-white rounded p-3" style="color: black;">Consulter ma candidature</a>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="offres" class="services section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Nos Offres</h2>
    <div><span class="description-title">Consultez les offres disponibles</span></div>
  </div>

  <div class="container">
    <div class="row gy-4">
      @forelse($offres as $offre)
        <div class="col-lg-4 col-md-6" data-aos="fade-up">
          <div class="service-item position-relative">
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




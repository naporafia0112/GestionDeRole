@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.directeur') }}">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('stages.candidats_en_cours') }}">Candidats</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $candidat->prenoms }} {{ $candidat->nom }}</li>
                        </ol>
                    </div>
                    <div>
                        <h2 class="page-title"> Profil du Candidat</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h2 class="fw-bold mb-1"></h2>
    </div>

    <div class="row">
        <!-- Colonne Informations personnelles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Nom complet</span>
                                <span class="info-value">{{ $candidat->prenoms }} {{ $candidat->nom }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ $candidat->email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Téléphone</span>
                                <span class="info-value">{{ $candidat->telephone ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Ville</span>
                                <span class="info-value">{{ $candidat->ville ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Quartier</span>
                                <span class="info-value">{{ $candidat->quartier ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <span class="info-label">Type de dépôt</span>
                                <span class="info-value badge bg-primary text-dark">{{ ucfirst($candidat->type_depot ?? '-') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Progression du stage -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-semibold">Progression du stage</h5>
                </div>
                <div class="card-body">
                    @if($stage)
                        <div class="stage-info mb-4">
                            <div class="info-item mb-3">
                                <span class="info-label">Sujet</span>
                                <span class="info-value">{{ $stage->sujet ?? '-' }}</span>
                            </div>
                            <div class="info-item mb-3">
                                <span class="info-label">Lieu</span>
                                <span class="info-value">{{ $stage->lieu ?? '-' }}</span>
                            </div>
                            <div class="info-item mb-3">
                                <span class="info-label">Période</span>
                                <span class="info-value">
                                    {{ optional($stage->date_debut)->format('d/m/Y') ?? '-' }} → {{ optional($stage->date_fin)->format('d/m/Y') ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="progression-container">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Avancement</span>
                                <span class="fw-bold">{{ $progression ?? 0 }}% complété</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-gradient"
                                     role="progressbar"
                                     style="width: {{ $progression ?? 0 }}%;"
                                     aria-valuenow="{{ $progression ?? 0 }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Début</small>
                                <small class="text-muted">Fin</small>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-outline bg-dark" data-bs-toggle="modal" data-bs-target="#calendarModal">
                                <span class="text-light"><i class="bi bi-calendar3 me-1"></i>Voir le calendrier</span></label>
                            </button>
                        </div>
                    @else
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-secondary mb-3"></i>
                            <h6 class="fw-semibold">Aucun stage en cours</h6>
                            <p class="text-muted small">Ce candidat n'a pas de stage actif</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- MODAL -->
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-body p-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
          <i data-feather="x"></i>
        </button>
        <div id="calendar-container" class="p-4">
          <input type="text" id="calendar" class="form-control flatpickr-input" readonly style="display: none;">
        </div>
      </div>
    </div>
  </div>
</div>

<style>
    :root {
        --primary-color: #10b981;
        --secondary-color: #084833;
        --text-color: #2d3748;
        --text-light: #718096;
        --border-radius: 10px;
        --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card {
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: all 0.3s ease;
        background: #fff;
    }

    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-label {
        display: block;
        font-size: 0.8rem;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 500;
        color: var(--text-color);
        display: block;
        word-wrap: break-word;
    }

    .progress {
        border-radius: 100px;
        background-color: #f1f5f9;
    }

    .progress-bar.bg-gradient {
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        transition: width 0.4s ease;
    }

    .empty-state {
        color: var(--text-light);
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        font-size: 0.9rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--text-light);
    }

    .breadcrumb-item.active {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
    }
     #calendarModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
  }


  #calendar-container {
    background: #f8fafc;
  }

  .flatpickr-calendar {
    box-shadow: none;
    margin: 0 auto;
    max-width: 300px;
  }
  .flatpickr-weekday {
    font-weight: 500;
  }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Initialisation -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#calendar", {
            inline: true,
            defaultDate: "{{ $stage ? $stage->date_debut->toDateString() : now()->toDateString() }}",
            minDate: "{{ $stage ? $stage->date_debut->toDateString() : null }}",
            maxDate: "{{ $stage ? $stage->date_fin->toDateString() : null }}"
        });
    });
</script>
@endsection


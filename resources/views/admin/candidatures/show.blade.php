@extends('layouts.home')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="container mt-4">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('offres.candidatures', $candidature->offre->id) }}">Candidatures</a></li>
                        <li class="breadcrumb-item active"><strong>Détails de la candidature</strong></li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <strong>
                        N°{{ str_pad($numero, 3, '0', STR_PAD_LEFT) }} <small class="text-muted ms-2">(ID: {{ $candidature->uuid }})</small>
                    </strong>
                </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- Colonne gauche - Infos candidat et offre -->
        <div class="{{ !$candidature->cv_fichier && !$candidature->lm_fichier && !$candidature->lr_fichier ? 'col-lg-12' : 'col-lg-8' }}">
            <div class="card d-block h-100">
                <div class="card-body">

                    <div class="float-sm-end mb-2">
                        <div class="btn-group">
                            <div class="d-flex align-items-center gap-2">
                                <div class="col-auto">
                              <a href="{{ route('offres.candidatures', $candidature->offre->id) }}" class="btn btn-sm btn-link"><i class="mdi mdi-keyboard-backspace"></i>Retour</a>

                                </div>
                                        @if($candidature->statut === 'en_cours')
                                            <form action="{{ route('candidatures.retenir', $candidature->id) }}" method="POST" class="d-inline confirm-action" data-message="Confirmer la retenue ?">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success" title="Retenir"><i class="mdi mdi-check-circle-outline"></i></button>
                                            </form>
                                            <form action="{{ route('candidatures.reject', $candidature->id) }}" method="POST" class="d-inline confirm-action" data-message="Confirmer le rejet ?">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-danger ms-1" title="Rejeter"><i class="mdi mdi-close-circle-outline"></i></button>
                                            </form>
                                        @endif

                                        @if($candidature->statut !== 'rejete')
                                            <button class="btn btn-sm btn-outline-primary ms-1 analyze-btn" data-id="{{ $candidature->id }}" title="Analyser">
                                                <i class="mdi mdi-robot"></i>
                                            </button>
                                        @endif

                                        @if($candidature->statut === 'retenu' && !$candidature->entretien)
                                            <a href="{{ route('entretiens.slots.page', ['id_candidat' => $candidature->candidat->id, 'id_offre' => $candidature->offre->id]) }}" class="btn btn-sm btn-outline-info ms-1" title="Choisir un créneau">
                                                <i class="mdi mdi-calendar-check-outline"></i>
                                            </a>
                                        @endif


                                        @if($candidature->statut === 'retenu' && $candidature->entretien && $candidature->entretien->statut === 'effectuee')
                                            <form method="POST" action="{{ route('candidatures.valider', $candidature->id) }}" class="d-inline ms-1">
                                                @csrf
                                                <button class="btn btn-sm btn-success confirm-validate" title="Valider">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            </form>
                                        @endif
                            </div>
                        </div>
                    </div>

                    <!-- Infos candidat -->
                    <h4 class="mb-3 mt-0 font-18"><strong>Informations sur le candidat</strong></h4>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Nom :</strong></label>
                            <p><strong>{{ $candidature->candidat->nom }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Prénoms :</strong></label>
                            <p><strong>{{ $candidature->candidat->prenoms }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Type de dépôt :</strong></label>
                            <p><strong>{{ $candidature->candidat->type_depot ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Email :</strong></label>
                            <p><strong>{{ $candidature->candidat->email }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Téléphone :</strong></label>
                            <p><strong>{{ $candidature->candidat->telephone ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Ville :</strong></label>
                            <p><strong>{{ $candidature->candidat->ville ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Statut de la candidature :</strong></label>
                            @if($candidature->statut === 'en_cours')
                                <span class="badge bg-warning text-dark">En cours de traitement</span>
                            @elseif($candidature->statut === 'retenu')
                                <span class="badge bg-success">Retenu</span>
                            @elseif($candidature->statut === 'rejete')
                                <span class="badge bg-danger">Rejeté</span>
                             @elseif($candidature->statut === 'valide')
                                <span class="badge bg-success">Validé</span>
                            @else
                                <span class="badge bg-secondary">Inconnu</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Quartier :</strong></label>
                            <p><strong>{{ $candidature->candidat->quartier ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1"><strong>Statut de l'entretien :</strong></label>
                            @if($candidature->entretien)
                                @if($candidature->entretien->statut === 'effectuee')
                                    <span class="badge bg-success">Effectué</span>
                                @elseif($candidature->entretien->statut === 'prevu')
                                    <span class="badge bg-warning text-dark">Programmé</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($candidature->entretien->statut) }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Non programmé</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Infos de l'offre associée -->
                    <h4 class="mb-3 mt-0 font-18 text-primary"><strong>Offre associée</strong></h4>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Localisation :</strong></label>
                            <p>
                                <i class="mdi mdi-map-marker text-danger me-1"></i>
                                <strong>{{ $candidature->offre->localisation->pays ?? '-' }}</strong>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Statut :</strong></label>
                            <span class="badge bg-{{ $candidature->offre->statut == 'publie' ? 'success' : 'warning' }}">
                                <strong>{{ ucfirst($candidature->offre->statut) }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Date de publication :</strong></label>
                            <p><strong>{{ $candidature->offre->date_publication?->format('d/m/Y') ?? '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <label class="mt-2 mb-1"><strong>Date limite :</strong></label>
                            <p><strong>{{ $candidature->offre->date_limite?->format('d/m/Y') ?? '-' }}</strong></p>
                        </div>
                    </div>

                    <label class="mt-3 mb-1"><strong>Département :</strong></label>
                    <div class="p-2 rounded mb-3">
                        <p><strong>{{ $candidature->offre->departement ?? '-' }}</strong></p>
                    </div>

                    <label class="mt-3 mb-1"><strong>Description :</strong></label>
                    <div class="p-2 rounded mb-3">
                        {!! nl2br(e($candidature->offre->description ?? '-')) !!}
                    </div>

                    <label class="mt-3 mb-1"><strong>Exigences :</strong></label>
                    <div class="p-3 rounded">
                        {!! nl2br(e($candidature->offre->exigences ?? '-')) !!}
                    </div>
                </div>
            </div>
        </div>

        @if($candidature->cv_fichier || $candidature->lm_fichier || $candidature->lr_fichier)
            <!-- Colonne droite - Fichiers PDF -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><strong>Fichiers joints</strong></h5>

                        @foreach ([
                            'cv_fichier' => 'CV',
                            'lm_fichier' => 'Lettre de motivation',
                            'lr_fichier' => 'Lettre de recommandation'
                        ] as $champ => $label)
                            @php
                                $fichier = $candidature->$champ;
                                $exists = $fichier && Storage::disk('public')->exists($fichier);
                            @endphp

                            @if($exists)
                                <div class="mb-4">
                                    <label class="form-label"><strong>{{ $label }}</strong></label>
                                    <embed
                                        src="{{ route('candidatures.preview', ['id' => $candidature->id, 'field' => $champ]) }}"
                                        type="application/pdf"
                                        width="100%"
                                        height="200px"
                                        class="border rounded">

                                    <a href="{{ route('candidatures.download', ['id' => $candidature->id, 'field' => $champ]) }}"
                                    class="btn btn-outline-{{
                                        $champ == 'cv_fichier' ? 'primary' :
                                        ($champ == 'lm_fichier' ? 'success' : 'warning')
                                    }} mt-2 w-100">
                                        <i class="dripicons-download"></i> Télécharger {{ strtolower($label) }}
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Fichier {{ strtolower($label) }} introuvable.
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function () {
    const table = $('#candidatures-datatable').DataTable({
        responsive: true,
        order: [[3, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        columnDefs: [{ orderable: false, targets: [6] }]
    });

    // Filtrage par onglets
    $('.nav-link[data-status]').on('click', function () {
        const selected = $(this).data('status');
        table.column(2).search('');
        table.rows().every(function () {
            const rowStatut = $(this.node()).attr('data-statut');
            $(this.node()).toggle(!selected || rowStatut === selected);
        });
        table.draw(false);
    });

    //SweetAlert pour toutes les confirmations d'action
    $(document).on('submit', '.confirm-action', function (e) {
        e.preventDefault();
        const form = this;
        const message = $(form).data('message') || "Êtes-vous sûr de vouloir effectuer cette action ?";
        Swal.fire({
            title: 'Confirmation',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    $(document).on('click', '.confirm-validate', function (e) {
        e.preventDefault();
        const form = $(this).closest('form')[0];
        Swal.fire({
            title: 'Valider la candidature ?',
            text: "Cette action est irréversible.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Valider',
            cancelButtonText: 'Annuler',
            customClass: {
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Bouton d'analyse
    $('.analyze-btn').on('click', function () {
        const btn = $(this);
        const id = btn.data('id');
        btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');

        fetch(`/candidatures/${id}/analyze`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            $('.score-' + id).html(`<span class="badge bg-light text-dark">${data.score}/100</span>`);
            $('.commentaire-' + id).text(data.commentaire);
        })
        .catch(() => {
            $('.score-' + id).text('Erreur');
            $('.commentaire-' + id).text('Analyse échouée');
        })
        .finally(() => {
            btn.prop('disabled', false).html('<i class="mdi mdi-robot"></i>');
        });
    });
});
</script>
@endpush

@extends('layouts.home')

@section('content')
@php
    $labels = [
        'en_cours' => 'warning',
        'retenu' => 'success',
        'valide' => 'primary',
        'rejete' => 'danger',
        'effectuee' => 'info',
    ];

    $noms = [
        '' => 'Tous',
        'en_cours' => 'Non traité',
        'retenu' => 'Retenu',
        'valide' => 'Validé',
        'rejete' => 'Rejeté'
    ];
@endphp

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- En-tête amélioré avec plus d'espace -->
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="page-title mb-0" style='font-size: 20px;'>
                           {{ $offre->titre }}
                        </h2>
                    </div>
                    <div class="col-md-6">
                        <div class="page-title-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                                    <li class="breadcrumb-item active">Liste des candidatures</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Bootstrap pour filtrer avec meilleur espacement -->
            <div class="mb-4">
                <ul class="nav nav-tabs" id="statutTabs" role="tablist">
                    @foreach($noms as $key => $label)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif" data-status="{{ $key }}" data-bs-toggle="tab" type="button">
                                {{ $label }}
                                <span class="badge bg-light text-dark ">
                                    {{ $key === '' ? $offre->candidatures->count() : (isset($candidaturesParStatut[$key]) ? $candidaturesParStatut[$key]->count() : 0) }}
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Table avec meilleur espacement -->
            <div class="table-responsive">
                <table id="candidatures-datatable" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Candidat</th>
                            <th width="15%">Statut</th>
                            <th width="15%">Date de soumission</th>
                            <th width="10%">Score</th>
                            <th width="20%">Commentaire</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offre->candidatures as $candidature)
                            <tr data-statut="{{ $candidature->statut }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <p class="fw-bold mb-0">{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $labels[$candidature->statut] ?? 'secondary' }} rounded-pill">
                                        {{ \App\Models\Candidature::STATUTS[$candidature->statut] ?? ucfirst($candidature->statut) }}
                                    </span>
                                </td>
                                <td>{{ $candidature->date_soumission->format('d/m/Y') }}</td>
                                <td class="score-{{ $candidature->id }}">
                                    @if($candidature->score !== null)
                                        <span class="badge bg-light text-dark">{{ $candidature->score }}/100</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="commentaire-{{ $candidature->id }}">
                                    {{ Str::limit($candidature->commentaire ?? '-', 50) }}
                                </td>
                                <td class="text-center action-buttons">
                                    <div class="d-flex justify-content-center">
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
                                            <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-info ms-1" title="Voir">
                                                <i class="fe-eye"></i>
                                            </a>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
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

    // Tabs Bootstrap : filtrage dynamique
    $('.nav-link[data-status]').on('click', function () {
        const selected = $(this).data('status');
        table.column(2).search(''); // Clear previous search
        if (selected) {
            table.rows().every(function () {
                const rowStatut = $(this.node()).attr('data-statut');
                $(this.node()).toggle(rowStatut === selected);
            });
        } else {
            table.rows().every(function () {
                $(this.node()).show();
            });
        }
        table.draw(false);
    });

    // SweetAlert confirmations
    $('.confirm-action').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Confirmation',
            text: $(form).data('message') || "Confirmer ?",
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

    $('.confirm-validate').on('click', function (e) {
        e.preventDefault();
        const form = $(this).closest('form')[0];
        Swal.fire({
            title: 'Valider la candidature ?',
            text: "Cette action est irréversible.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
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
@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
<style>
    /* Styles personnalisés pour améliorer l'espacement */
    .page-header {
        margin-bottom: 2rem;
    }

    .card-body {
        padding: 2rem;
    }

    .nav-tabs {
        margin-bottom: 1.5rem;
    }

    .table-responsive {
        margin-top: 1.5rem;
    }

    .table th, .table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    .action-buttons .btn {
        margin: 0 3px;
        padding: 0.35rem 0.65rem;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
    }
</style>
@endpush

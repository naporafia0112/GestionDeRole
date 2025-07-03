@extends('layouts.home')

@section('content')
<div class="container mt-4">
   <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">DIPRH</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('offres.index') }}">Liste des offres</a></li>
                                <li class="breadcrumb-item active">Liste des candidatures</li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            Liste des candidatures pour l'offre : <strong>{{ $offre->titre }}</strong>
                        </h4>
                    </div>
                </div>

                <div class="col-sm-6 mt-2">
                </div>
                <div class="col-12 col-sm-6 text-sm-end mt-2">
                    <select id="statut-filter" class="form-select w-auto d-inline-block">
                        <option value="">Tous les statuts</option>
                        <option value="retenu">Retenu</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                        <option value="en_cours">Non traité</option>
                    </select>
                </div>
            </div>

            @if($offre->candidatures->isEmpty())
                <div class="alert alert-info">Aucune candidature enregistrée.</div>
            @else
                <div class="table-responsive">
                    <table id="candidatures-datatable" class="table table-centered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Candidat</th>
                                <th>Statut</th>
                                <th>Date de soumission</th>
                                <th>Score</th>
                                <th>Commentaire</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offre->candidatures as $candidature)
                                @php
                                    $statut = $candidature->statut;
                                    $labels = [
                                        'en_cours' => 'warning',
                                        'retenu' => 'success',
                                        'valide' => 'primary',
                                        'rejete' => 'danger',
                                        'effectuee' => 'info',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $candidature->candidat->nom }} {{ $candidature->candidat->prenoms }}</td>
                                    <td data-statut="{{ $statut }}">
                                        <span class="badge bg-{{ $labels[$statut] ?? 'secondary' }}">
                                            {{ \App\Models\Candidature::STATUTS[$statut] ?? ucfirst($statut) }}
                                        </span>
                                    </td>
                                    <td>{{ $candidature->date_soumission->format('d/m/Y') }}</td>
                                    <td class="score-{{ $candidature->id }}">
                                        {{ $candidature->score !== null ? $candidature->score . ' / 100' : '-' }}
                                    </td>
                                    <td class="commentaire-{{ $candidature->id }}">
                                        {{ $candidature->commentaire ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-2">

                                            {{-- Retenir --}}
                                            @if($statut === 'en_cours')
                                                <form action="{{ route('candidatures.retenir', $candidature->id) }}"
                                                    method="POST" class="d-inline confirm-action"
                                                    data-message="Confirmer la retenue de cette candidature ?">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-outline-success me-1" type="submit" title="Retenir">
                                                        <i class="mdi mdi-check-circle-outline"></i>
                                                    </button>
                                                </form>

                                                {{-- Rejeter --}}
                                                <form action="{{ route('candidatures.reject', $candidature->id) }}"
                                                    method="POST" class="d-inline confirm-action"
                                                    data-message="Confirmer le rejet de cette candidature ?">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit" title="Rejeter">
                                                        <i class="mdi mdi-close-circle-outline"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Planifier entretien --}}
                                            @if($statut === 'retenu')
                                                <a href="{{ route('entretiens.create', ['id_candidat' => $candidature->candidat->id, 'id_offre' => $candidature->offre->id]) }}"
                                                    class="btn btn-sm btn-outline-info"
                                                    title="Planifier entretien">
                                                    <i class="mdi mdi-calendar-check-outline"></i>
                                                </a>
                                            @endif

                                            {{-- Analyser --}}
                                            @if($statut !== 'rejete')
                                                <button class="btn btn-sm btn-outline-primary analyze-btn" data-id="{{ $candidature->id }}" title="Analyser">
                                                    <i class="mdi mdi-robot"></i>
                                                </button>
                                               <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-info" title="Voir détails">
                                                    <i class="fe-eye"></i>
                                                </a>                                                
                                            @endif
                                            {{-- Valider (si score >= 80 et statut = retenu) --}}
                                            @if($candidature->statut === 'retenu' && $candidature->score >= 80)
                                                <form method="POST" action="{{ route('candidatures.valider', $candidature->id) }}" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success confirm-validate" type="button" title="Valider">
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
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        // Confirmation pour les actions via formulaire avec data-message
        $('.confirm-action').on('submit', function (e) {
            e.preventDefault();
            const form = this;
            const message = $(form).data('message') || "Êtes-vous sûr de vouloir continuer ?";

            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Confirmation spéciale pour le bouton "Valider"
        $('.confirm-validate').on('click', function (e) {
            e.preventDefault();
            const form = $(this).closest('form')[0];

            Swal.fire({
                title: 'Valider la candidature ?',
                text: "Cette action validera définitivement cette candidature.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler',
                customClass: {
                    confirmButton: 'btn btn-success me-2',
                    cancelButton: 'btn btn-outline-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function () {
        var table = $('#candidatures-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            responsive: true,
            order: [[3, 'desc']],
            columnDefs: [{ orderable: false, targets: [6] }]
        });

        // Filtrage par statut
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var selectedStatut = $('#statut-filter').val();
            if (!selectedStatut) return true;
            var row = table.row(dataIndex).node();
            var statutCell = row.querySelector('td[data-statut]');
            if (statutCell) {
                return statutCell.getAttribute('data-statut') === selectedStatut;
            }
            return true;
        });

        $('#statut-filter').on('change', function () {
            table.draw();
        });

        // Analyse IA toutes les candidatures
        $('#analyzeAllBtn').click(function () {
            const btn = $(this);
            btn.prop('disabled', true).text('Analyse en cours...');

            const candidatures = @json($offre->candidatures);

            let promises = [];

            candidatures.forEach(candidature => {
                let p = fetch(`/candidatures/${candidature.id}/analyze`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("Erreur HTTP " + res.status);
                    return res.json();
                })
                .then(data => {
                    $('.score-' + candidature.id).text(data.score + ' / 100');
                    $('.commentaire-' + candidature.id).text(data.commentaire);
                })
                .catch(() => {
                    $('.score-' + candidature.id).text("Erreur");
                    $('.commentaire-' + candidature.id).text("Analyse échouée");
                });

                promises.push(p);
            });

            Promise.all(promises).finally(() => {
                btn.prop('disabled', false).text('Analyser toutes les candidatures');
            });
        });

        // Analyse IA pour un seul CV
        $('.analyze-btn').click(function () {
            const btn = $(this);
            const id = btn.data('id');

            btn.prop('disabled', true);
            const originalHtml = btn.html();
            btn.html('<i class="mdi mdi-loading mdi-spin"></i>');

            fetch(`/candidatures/${id}/analyze`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => {
            if (!res.ok) {
                if(res.status === 404) {
                    throw new Error('Fichier introuvable');
                } else {
                    throw new Error('Erreur HTTP ' + res.status);
                }
            }
            return res.json();
        })
            .then(data => {
                $('.score-' + id).text(data.score + ' / 100');
                $('.commentaire-' + id).text(data.commentaire);
            })
            .catch((error) => {
            if(error.message === 'Fichier introuvable') {
                $('.score-' + id).text('Erreur');
                $('.commentaire-' + id).text('Fichier introuvable');
            } else {
                $('.score-' + id).text('Erreur');
                $('.commentaire-' + id).text('Analyse échouée');
            }
            })
            .finally(() => {
                btn.prop('disabled', false);
                btn.html(originalHtml);
            });
        });
    });
</script>
@endpush

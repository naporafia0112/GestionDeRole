@extends('layouts.home')

@section('content')
@php
    $labels = [
        'reçue' => 'warning',
        'retenu' => 'success',
        'valide' => 'primary',
        'rejete' => 'danger',
        'effectuee' => 'info',
    ];

    $noms = [
        '' => 'Tous',
        'reçue' => 'Non traité',
        'retenu' => 'Retenu',
        'valide' => 'Validé',
        'rejete' => 'Rejeté'
    ];
@endphp
<div class="container mt-4">
     <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                    <li class="breadcrumb-item active">Candidatures Spontanées</li>
                                </ol>
                            </nav>
                        </div>
                        <h4 class="page-title"><strong>Listes des candidatures Spontanées</strong></h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="candidatures-spontanees-table" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Candidat</th>
                            <th>Contact</th>
                            <th>Localisation</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidatures as $loopIndex => $c)
                            <tr>
                                <td>{{ $loopIndex + 1 }}</td>
                                <td><strong>{{ $c->candidat->nom }} {{ $c->candidat->prenoms }}</strong></td>
                                <td>
                                    <div>{{ $c->candidat->email }}</div>
                                    <div>{{ $c->candidat->telephone }}</div>
                                </td>
                                <td>{{ $c->candidat->ville }} / {{ $c->candidat->quartier }}</td>
                               <td>
                                     <span class="badge bg-{{ $labels[$c->statut] ?? 'secondary' }} rounded-pill">
                                        {{ \App\Models\CandidatureSpontanee::STATUTS[$c->statut] ?? ucfirst($c->statut) }}
                                    </span>
                                </td>

                                <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('candidatures.spontanees.show', $c->id) }}" class="btn btn-sm btn-info ms-1" title="Voir">
                                            <i class="fe-eye"></i>
                                        </a>

                                        {{--@if($c->statut !== 'retenu')
                                            <form action="{{ route('candidatures.spontanees.retenir', $c->id) }}" method="POST" class="d-inline ms-1 confirm-action" data-message="Retenir cette candidature ?">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning" title="Retenir">
                                                    <i class="mdi mdi-star-outline"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('candidatures.spontanees.valider', $c->id) }}" method="POST" class="d-inline ms-1 confirm-action" data-message="Valider cette candidature ?">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success" title="Valider" {{ $c->statut !== 'retenu' ? 'disabled' : '' }}>
                                                <i class="mdi mdi-check-circle-outline"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('candidatures.spontanees.rejeter', $c->id) }}" method="POST" class="d-inline ms-1 confirm-action" data-message="Rejeter cette candidature ?">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-danger" title="Rejeter">
                                                <i class="mdi mdi-close-circle-outline"></i>
                                            </button>
                                        </form>--}}
                                        @if($c->statut === 'reçue')
                                            <form action="{{ route('candidatures.spontanees.retenir', $c->id) }}" method="POST" class="d-inline confirm-action" data-message="Confirmer la retenue ?">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success ms-1" title="Retenir"><i class="mdi mdi-check-circle-outline"></i></button>
                                            </form>
                                            <form action="{{ route('candidatures.spontanees.rejeter', $c->id) }}" method="POST" class="d-inline confirm-action" data-message="Confirmer le rejet ?">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-sm btn-outline-danger ms-1" title="Rejeter"><i class="mdi mdi-close-circle-outline"></i></button>
                                            </form>
                                        @endif

                                        @if($c->statut === 'retenu' && !$c->a_un_entretien_effectue)
                                            <a href="{{ route('entretiens.slots.page', ['id_candidat' => $c->candidat->id]) }}" class="btn btn-sm btn-outline-info ms-1" title="Choisir un créneau">
                                                <i class="mdi mdi-calendar-check-outline"></i>
                                            </a>
                                        @endif
                                        @if($c->statut === 'retenu' && $c->a_un_entretien_effectue)
                                            <form method="POST" action="{{ route('candidatures.spontanees.valider', $c->id)}}" class="d-inline ms-1">
                                                @csrf
                                                @method('PATCH')
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

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
<style>
    .table th, .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.6em;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#candidatures-spontanees-table').DataTable({
            responsive: true,
            order: [[5, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            columnDefs: [{ orderable: false, targets: [8] }]
        });

        $('.analyze-btn').on('click', function () {
            const btn = $(this);
            const id = btn.data('id');
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');

            fetch(`/candidatures-spontanees/${id}/analyse`, {
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
    });
</script>
@endpush

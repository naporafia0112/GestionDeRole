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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                <li class="breadcrumb-item">Liste des candidatures</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <strong>Liste des offres</strong>
                        </h4>
                    </div>
                </div>

            {{-- Tabs Bootstrap filtrant par statut --}}
            <ul class="nav nav-tabs mb-4" id="candidatureTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-statut="" type="button">Toutes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-statut="retenu" type="button">Retenus</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-statut="valide" type="button">Validés</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-statut="rejete" type="button">Rejetés</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-statut="en_cours" type="button">Non traités</button>
                </li>
            </ul>

            <div class="table-responsive">
                <table id="candidatures-datatable" class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>N°</th>
                            <th>Candidat</th>
                            <th>Offre</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidatures as $candidature)
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
                                <td>{{ $candidature->offre->titre ?? 'Offre supprimée' }}</td>
                                <td data-statut="{{ $statut }}">
                                    <span class="badge bg-{{ $labels[$statut] ?? 'secondary' }}">
                                        {{ \App\Models\Candidature::STATUTS[$statut] ?? ucfirst($statut) }}
                                    </span>
                                </td>
                                <td>{{ $candidature->date_soumission->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('candidatures.show', $candidature->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- pagination Laravel --}}
                <div class="mt-3">
                    {{ $candidatures->links() }}
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        var table = $('#candidatures-datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            responsive: true,
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        });

        // Fonction filtre personnalisé par statut
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            let statutFilter = $('#candidatureTabs .nav-link.active').data('statut');
            if (!statutFilter) return true; // aucune sélection = tout afficher
            let row = table.row(dataIndex).node();
            let td = row.querySelector('td[data-statut]');
            return td && td.getAttribute('data-statut') === statutFilter;
        });

        // Changement onglet -> changement filtre + redraw table
        $('#candidatureTabs .nav-link').on('click', function () {
            $('#candidatureTabs .nav-link').removeClass('active');
            $(this).addClass('active');
            table.draw();
        });
    });
</script>
@endpush

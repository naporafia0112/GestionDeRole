@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2 align-items-center">
                <div class="col-6">
                    <h4 class="page-title">Toutes les candidatures</h4>
                </div>
                <div class="col-6 text-end">
                    <select id="statut-filter" class="form-select w-auto d-inline-block">
                        <option value="">Tous les statuts</option>
                        <option value="retenu">Retenu</option>
                        <option value="valide">Validé</option>
                        <option value="rejete">Rejeté</option>
                        <option value="en_cours">Non traité</option>
                    </select>
                </div>
            </div>

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
                {{-- pagination Laravel si tu ne veux pas tout charger d'un coup --}}
                <div class="mt-3">
                    {{ $candidatures->links() }}
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

        // filtre personnalisé par statut
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            let statut = $('#statut-filter').val();
            if (!statut) return true;
            let row = table.row(dataIndex).node();
            let td = row.querySelector('td[data-statut]');
            return td && td.getAttribute('data-statut') === statut;
        });

        $('#statut-filter').on('change', function () {
            table.draw();
        });
    });
</script>
@endpush

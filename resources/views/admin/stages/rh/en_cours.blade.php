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
                                    <li class="breadcrumb-item"><a href="{{ route('rh.stages.en_cours') }}">Liste des stages en cours</a></li>
                                </ol>
                            </div>
                            <h4 class="page-title">
                                <strong>stage en cours</strong>
                            </h4>
                        </div>
                    </div>

            {{-- Onglets Bootstrap --}}
            <ul class="nav nav-tabs" id="typeDepotTabs" role="tablist">
                @foreach ($typesDepot as $index => $typeDepot)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($index === 0) active @endif"
                                id="tab-{{ Str::slug($typeDepot) }}"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-content-{{ Str::slug($typeDepot) }}"
                                type="button"
                                role="tab"
                                aria-controls="tab-content-{{ Str::slug($typeDepot) }}"
                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ ucfirst($typeDepot) }} ({{ $stagesParType[$typeDepot]->count() }})
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Contenu onglets --}}
            <div class="tab-content mt-3" id="typeDepotTabsContent">
                @foreach ($typesDepot as $index => $typeDepot)
                    <div class="tab-pane fade @if($index === 0) show active @endif"
                         id="tab-content-{{ Str::slug($typeDepot) }}"
                         role="tabpanel"
                         aria-labelledby="tab-{{ Str::slug($typeDepot) }}">
                        @if($stagesParType[$typeDepot]->isEmpty())
                            <div class="alert alert-info">
                                Aucun stage de type {{ ucfirst($typeDepot) }} trouvé.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table id="datatable-{{ Str::slug($typeDepot) }}"
                                       class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Candidat</th>
                                            <th>Offre</th>
                                            <th>Tuteur</th>
                                            <th>Statut</th>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stagesParType[$typeDepot] as $stage)
                                            <tr>
                                                <td>{{ $stage->candidat->nom ?? '' }} {{ $stage->candidat->prenoms ?? '' }}</td>
                                                <td>{{ $stage->candidature->offre->titre ?? 'Pas d\'offre' }}</td>
                                                <td>{{ $stage->tuteur->name ?? 'Non défini' }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $stage->statut)) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stage->date_debut)->format('d/m/Y') ?? '---' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($stage->date_fin)->format('d/m/Y') ?? '---' }}</td>
                                                <td>
                                                    <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                    <a href="{{ route('stages.show', $stage->id) }}" class="btn btn-sm btn-info" title="Voir">
                                                        <i class="fe-eye"></i>
                                                    </a>
                                                    @if($stage->validation_directeur)

                                                        <a href="{{ route('stages.edit', $stage->id) }}" class="btn btn-success btn-sm">
                                                            <i class="mdi mdi-check-circle-outline"></i> Terminer stage
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
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
$(document).ready(function() {
    @foreach ($typesDepot as $typeDepot)
        $('#datatable-{{ Str::slug($typeDepot) }}').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            responsive: true,
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            order: [[4, 'desc']]
        });
    @endforeach

    // Ajuster les colonnes DataTables quand on change d'onglet
    var triggerTabList = [].slice.call(document.querySelectorAll('#typeDepotTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        triggerEl.addEventListener('shown.bs.tab', function (event) {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        })
    });
});
</script>
@endpush

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

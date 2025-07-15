@extends('layouts.home')

@section('content')
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
                                    <li class="breadcrumb-item active">Liste des entretiens</li>
                                </ol>
                            </nav>
                        </div>
                        <h4 class="mb-4" style="font-size: 25px">Liste de tout les entretiens</h4>
                    </div>
                </div>
            </div>
            <!-- Onglets Bootstrap pour filtrer par statut -->
            <div class="mb-3">
                <strong>Filtrer par statut :</strong>
                <ul class="nav nav-tabs" id="tabsStatut" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-statut="" type="button" role="tab" aria-selected="true">
                            Tous <span class="badge bg-secondary" id="count-statut-tous"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-statut="prevu" type="button" role="tab" aria-selected="false">
                            Prévu <span class="badge bg-secondary" id="count-statut-prevu"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-statut="annule" type="button" role="tab" aria-selected="false">
                            Annulé <span class="badge bg-secondary" id="count-statut-annule"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-statut="encours" type="button" role="tab" aria-selected="false">
                            En cours <span class="badge bg-secondary" id="count-statut-encours"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-statut="effectuee" type="button" role="tab" aria-selected="false">
                            Effectuée <span class="badge bg-secondary" id="count-statut-effectuee"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-statut="termine" type="button" role="tab" aria-selected="false">
                            Terminé <span class="badge bg-secondary" id="count-statut-termine"></span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Onglets Bootstrap pour filtrer par type de candidature -->
            <div class="mb-3">
                <strong>Filtrer par type de candidature :</strong>
                <ul class="nav nav-tabs" id="tabsTypeCandidature" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-type="" type="button" role="tab" aria-selected="true">
                            Tous <span class="badge bg-secondary" id="count-type-tous"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-type="offre" type="button" role="tab" aria-selected="false">
                            Offre <span class="badge bg-secondary" id="count-type-offre"></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-type="spontanee" type="button" role="tab" aria-selected="false">
                            Spontanée <span class="badge bg-secondary" id="count-type-spontanee"></span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="table-responsive">
                <table id="entretiens-table" class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Candidat</th>
                            <th>Offre</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entretiens as $entretien)
                            <tr
                                data-statut="{{ strtolower($entretien->statut) }}"
                                data-type="{{ $entretien->offre ? 'offre' : 'spontanee' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entretien->candidat->nom ?? '-' }} {{ $entretien->candidat->prenoms ?? '' }}</td>
                                <td>{{ $entretien->offre->titre ?? 'Entretien spontané' }}</td>
                                <td>{{ \Carbon\Carbon::parse($entretien->date)->format('d/m/Y') }}</td>
                                <td>{{ $entretien->heure ? \Carbon\Carbon::parse($entretien->heure)->format('H:i') : '--' }}</td>
                                <td>
                                    <span class="badge bg-{{ $entretien->statut === 'annule' ? 'danger' : ($entretien->statut === 'effectuee' ? 'success' : 'warning') }}">
                                        {{ ucfirst($entretien->statut) }}
                                    </span>
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
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#entretiens-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            responsive: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-sm btn-danger',
                    text: '<i class="mdi mdi-file-pdf"></i> Exporter en PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        rows: function (idx, data, node) {
                            let statutFiltre = $('#tabsStatut .nav-link.active').data('statut');
                            let typeFiltre = $('#tabsTypeCandidature .nav-link.active').data('type');

                            let statutRow = $(node).data('statut');
                            let typeRow = $(node).data('type');

                            let statutOk = !statutFiltre || statutFiltre === "" ? true : statutRow === statutFiltre;
                            let typeOk = !typeFiltre || typeFiltre === "" ? true : typeRow === typeFiltre;

                            return statutOk && typeOk;
                        }
                    },
                    customize: function (doc) {
                        doc.styles.title = { alignment: 'center', fontSize: 14 };
                        doc.styles.tableHeader = { alignment: 'center', bold: true, fontSize: 12 };
                        doc.content.forEach(function (contentItem) {
                            if (contentItem.table && contentItem.table.body) {
                                contentItem.table.body.forEach(function (row) {
                                    row.forEach(function (cell) {
                                        if (typeof cell === 'object') {
                                            cell.alignment = 'center';
                                        }
                                    });
                                });
                            }
                        });
                    }
                }
            ]
        });

        // Fonction qui calcule et affiche le count dans chaque onglet
        function updateCounts() {
            let countsStatut = {
                tous: 0,
                prevu: 0,
                annule: 0,
                encours: 0,
                effectuee: 0,
                termine: 0
            };
            let countsType = {
                tous: 0,
                offre: 0,
                spontanee: 0
            };

            $('#entretiens-table tbody tr').each(function() {
                countsStatut.tous++;
                countsType.tous++;

                let statut = $(this).data('statut');
                let type = $(this).data('type');

                if (statut in countsStatut) countsStatut[statut]++;
                if (type in countsType) countsType[type]++;
            });

            $('#count-statut-tous').text(countsStatut.tous);
            $('#count-statut-prevu').text(countsStatut.prevu);
            $('#count-statut-annule').text(countsStatut.annule);
            $('#count-statut-encours').text(countsStatut.encours);
            $('#count-statut-effectuee').text(countsStatut.effectuee);
            $('#count-statut-termine').text(countsStatut.termine);

            $('#count-type-tous').text(countsType.tous);
            $('#count-type-offre').text(countsType.offre);
            $('#count-type-spontanee').text(countsType.spontanee);
        }

        function filtrerTable() {
            let statutFiltre = $('#tabsStatut .nav-link.active').data('statut');
            let typeFiltre = $('#tabsTypeCandidature .nav-link.active').data('type');

            table.rows().every(function () {
                let rowStatut = $(this.node()).data('statut');
                let rowType = $(this.node()).data('type');

                let statutOk = !statutFiltre || statutFiltre === "" ? true : rowStatut === statutFiltre;
                let typeOk = !typeFiltre || typeFiltre === "" ? true : rowType === typeFiltre;

                $(this.node()).toggle(statutOk && typeOk);
            });
        }

        // Clic onglets statut
        $('#tabsStatut .nav-link').on('click', function () {
            $('#tabsStatut .nav-link').removeClass('active');
            $(this).addClass('active');
            filtrerTable();
        });

        // Clic onglets type candidature
        $('#tabsTypeCandidature .nav-link').on('click', function () {
            $('#tabsTypeCandidature .nav-link').removeClass('active');
            $(this).addClass('active');
            filtrerTable();
        });

        updateCounts();
    });
</script>
@endpush

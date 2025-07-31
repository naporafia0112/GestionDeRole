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
                                <li class="breadcrumb-item"><a href="">Candidats en stage</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="mb-4">Candidats actuellement en stage</h4>

            @if ($candidats->isEmpty())
                <div class="alert alert-info">Aucun candidat en stage actuellement.</div>
            @else
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="min-date">Date début</label>
                    <input type="date" id="min-date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="max-date">Date fin</label>
                    <input type="date" id="max-date" class="form-control">
                </div>
            </div>
            <a href="#" id="export-excel" class="btn btn-success mb-2">
                <i class="mdi mdi-file-excel"></i> Exporter Excel
            </a>
            <a href="#"  id="export-pdf" class="btn btn-danger mb-2"><i class="mdi mdi-file-pdf-box"></i> Export PDF</a>
            <a href="#"  id="export-word" class="btn btn-primary mb-2"><i class="mdi mdi-file-word-box"></i> Export Word</a>
            <a href="#" target="_blank"  id="imprimer" class="btn btn-secondary mb-2"><i class="mdi mdi-printer"></i> Imprimer</a>


                <div class="table-responsive">
                    <table id="candidats-datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date début stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($candidats as $candidat)
                                <tr>
                                    <td>{{ $candidat->nom }}</td>
                                    <td>{{ $candidat->prenoms ?? $candidat->prenom ?? '' }}</td>
                                    <td>{{ $candidat->email ?? '---' }}</td>
                                    <td>{{ $candidat->telephone ?? '---' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($candidat->date_debut)->format('Y-m-d') }}</td>
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
<!-- DataTables js -->
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function getExportUrl(baseUrl) {
        const dateDebut = $('#min-date').val();
        const dateFin = $('#max-date').val();

        if (!dateDebut && !dateFin) {
            return baseUrl;
        }

        return `${baseUrl}?date_debut=${dateDebut}&date_fin=${dateFin}`;
    }

    $('#export-excel').on('click', function (e) {
        e.preventDefault();
        const url = getExportUrl("{{ route('candidats.export.tous') }}");
        window.location.href = url;
    });

    $('#export-pdf').on('click', function (e) {
        e.preventDefault();
        const url = getExportUrl("{{ route('candidats.export.pdf') }}");
        window.location.href = url;
    });

    $('#export-word').on('click', function (e) {
        e.preventDefault();
        const url = getExportUrl("{{ route('candidats.export.word') }}");
        window.location.href = url;
    });

    $('#imprimer').on('click', function (e) {
        e.preventDefault();
        const url = getExportUrl("{{ route('candidats.imprimer') }}");
        window.open(url, '_blank');
    });
</script>
@if (session('no_data'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Aucun résultat',
        text: '{{ session('no_data') }}',
    });
</script>
@endif

@endpush

@push('styles')
<!-- DataTables css -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

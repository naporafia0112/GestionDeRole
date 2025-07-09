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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.directeur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item active">Tuteurs</li>
                            </ol>
                        </div>
                        <h4 class="page-title">
                            <strong>Les tuteurs disponible</strong>
                        </h4>
                    </div>
                </div>
            </div>

            @if ($tuteurs->isEmpty())
                <div class="alert alert-warning">Aucun tuteur trouvé dans votre département.</div>
            @else
                <div class="table-responsive">
                    <table id="tuteurs-datatable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Département</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tuteurs as $tuteur)
                                <tr>
                                    <td>{{ $tuteur->name }}</td>
                                    <td>{{ $tuteur->email }}</td>
                                    <td>{{ $tuteur->departement->nom ?? 'Non défini' }}</td>
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

<script>
$(document).ready(function() {
    $('#tuteurs-datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true,
        pageLength: 10
    });
});
</script>
@endpush

@push('styles')
<!-- DataTables css -->
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />

<style>
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 4px;
        padding: 5px 10px;
        border: 1px solid #dee2e6;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 4px;
        padding: 5px;
        border: 1px solid #dee2e6;
    }
</style>
@endpush

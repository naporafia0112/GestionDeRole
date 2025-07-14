@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
             <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="page-title mb-0" style="font-size: 20px;">
                            Candidatures Spontanées
                        </h2>
                    </div>
                    <div class="col-md-6">
                        <div class="page-title-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                                    <li class="breadcrumb-item active">Liste des candidatures</li>
                                </ol>
                            </nav>
                        </div>
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
                            <th>Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidatures as $loopIndex => $c)
                            <tr>
                                <td>{{ $loopIndex + 1 }}</td>
                                <td>
                                    <strong>{{ $c->candidat->nom }} {{ $c->candidat->prenoms }}</strong>
                                </td>
                                <td>
                                    <div>{{ $c->candidat->email }}</div>
                                    <div>{{ $c->candidat->telephone }}</div>
                                </td>
                                <td>{{ $c->candidat->ville }} / {{ $c->candidat->quartier }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $c->candidat->type_depot }}</span>
                                </td>
                                <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                   <a href="{{ route('candidatures.spontanees.show', $c->id) }}" class="btn btn-sm btn-info ms-1" title="Voir">
                                        <i class="fe-eye"></i>
                                    </a>
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
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
@endpush

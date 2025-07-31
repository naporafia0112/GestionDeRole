@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="page-title">Formulaires archivés</h4>
                        <a href="{{ route('directeur.formulaires.liste') }}" class="btn btn-sm btn-secondary">Retour à la liste</a>
                    </div>
                    @if($formulaires->isEmpty())
                        <p>Aucun formulaire archivé.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de création</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formulaires as $formulaire)
                                    <tr>
                                        <td>{{ $formulaire->titre }}</td>
                                        <td>{{ $formulaire->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('table.table').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json"
        },
        pageLength: 10,
        lengthChange: false,
        searching: true,
        ordering: true,
    });
});
</script>
@endpush

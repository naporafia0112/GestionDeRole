@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="page-title-box d-flex justify-content-between align-items-center mb-4">
                <h4 class="page-title mb-0">Liste des attestations générées</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.RH') }}">DIPRH</a></li>
                    <li class="breadcrumb-item active">Attestations</li>
                </ol>
            </div>

            <div class="table-responsive">
                <table id="datatable-attestations" class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Nom du candidat</th>
                            <th>Type</th>
                            <th>Service</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Générée le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attestations as $attestation)
                            <tr>
                                <td>{{ $attestation->stage->candidature->candidat->nom ?? '---' }} {{ $attestation->stage->candidature->candidat->prenoms ?? '' }}</td>
                                <td>{{ ucfirst($attestation->type) }}</td>
                                <td>{{ $attestation->service }}</td>
                                <td>{{ \Carbon\Carbon::parse($attestation->debut)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attestation->fin)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attestation->date_generation)->format('d/m/Y') }}</td>
                                <td>
                                    <!-- Bouton aperçu PDF (modal conservé) -->
                                    <button class="btn btn-sm btn-secondary btn-preview"
                                        title="Aperçu PDF"
                                        data-bs-toggle="modal"
                                        data-bs-target="#previewPdfModal"
                                        data-preview-url="{{ route('attestations.export.pdf', $attestation) }}?forme=standard&action=view">
                                        <i class="mdi mdi-eye"></i>
                                    </button>

                                    <!-- Lien direct pour télécharger PDF -->
                                    <a href="{{ route('attestations.export.pdf', $attestation) }}?forme=standard"
                                    class="btn btn-sm btn-danger"
                                    title="Télécharger PDF"
                                    rel="noopener noreferrer">
                                        <i class="mdi mdi-file-pdf-box"></i>
                                    </a>

                                    <!-- Lien direct pour télécharger Word -->
                                    <a href="{{ route('attestations.export.word', $attestation) }}"
                                    class="btn btn-sm btn-primary"
                                    title="Télécharger Word"
                                    rel="noopener noreferrer">
                                        <i class="mdi mdi-file-word-box"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal de détail -->
            <div class="modal fade" id="attestationModal" tabindex="-1" aria-labelledby="attestationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="attestationModalLabel">Détail de l'attestation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Stagiaire :</strong> <span id="modal-nom"></span> <span id="modal-prenoms"></span></p>
                            <p><strong>Service :</strong> <span id="modal-service"></span></p>
                            <p><strong>Type :</strong> <span id="modal-type"></span></p>
                            <p><strong>Période :</strong> du <span id="modal-debut"></span> au <span id="modal-fin"></span></p>
                        </div>
                        <div class="modal-footer">
                            <a href="#" id="modal-pdf" class="btn btn-outline-danger" target="_blank">Télécharger PDF</a>
                            <a href="#" id="modal-word" class="btn btn-outline-primary" target="_blank">Télécharger Word</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Aperçu PDF -->
            <div class="modal fade" id="previewPdfModal" tabindex="-1" aria-labelledby="previewPdfModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Aperçu du PDF</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <iframe id="pdfPreviewFrame" src="" width="100%" height="600px" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('#datatable-attestations').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[5, 'desc']]
    });

    // Modal de détail
    $('#attestationModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#modal-nom').text(button.data('nom'));
        $('#modal-prenoms').text(button.data('prenoms'));
        $('#modal-service').text(button.data('service'));
        $('#modal-type').text(button.data('type'));
        $('#modal-debut').text(button.data('debut'));
        $('#modal-fin').text(button.data('fin'));
        $('#modal-pdf').attr('href', button.data('pdf'));
        $('#modal-word').attr('href', button.data('word'));
    });

    // Aperçu PDF dans modal
    $('#previewPdfModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let previewUrl = button.data('preview-url');
        $('#pdfPreviewFrame').attr('src', previewUrl);
    });
});
</script>
@endpush

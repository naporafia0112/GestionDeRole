@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="page-title">Formulaires archivés</h4>
                    </div>
                    <a href="{{ route('directeur.formulaires.liste') }}" class="btn btn-sm btn-link"><i class="mdi mdi-keyboard-backspace"></i>Retour</a>
                    @if($formulaires->isEmpty())
                        <p>Aucun formulaire archivé.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($formulaires as $formulaire)
                                    <tr>
                                        <td>{{ $formulaire->titre }}</td>
                                        <td>{{ $formulaire->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <form action="{{ route('directeur.formulaires.restore', $formulaire->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-light btn-sm" title="Restaurer ce formulaire" data-titre="{{ $formulaire->titre }}">
                                                    <i data-feather="rotate-cw" class="feather-sm"></i>
                                                </button>
                                            </form>
                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm btn-preview"
                                                data-id="{{ $formulaire->id }}"
                                                title="Aperçu">
                                                <i class="fe-eye"></i>
                                            </button>
                                        </td>
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

<!-- Modal Preview (copie depuis ta première page) -->
<div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="modalPreviewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPreviewLabel">Aperçu du formulaire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <div id="preview-content">Chargement...</div>
      </div>
      <div class="modal-footer">
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
    // Initialise DataTable
    $('table.table').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/fr-FR.json"
        },
        pageLength: 10,
        lengthChange: false,
        searching: true,
        ordering: true,
    });

    // Gérer le clic sur les boutons preview
    const modalPreview = new bootstrap.Modal(document.getElementById('modalPreview'));
    const previewContent = document.getElementById('preview-content');

    $('.btn-preview').on('click', function () {
        const formulaireId = $(this).data('id');
        previewContent.innerHTML = '<p class="text-center">Chargement...</p>';

        fetch(`/formulaires/${formulaireId}/preview`)
            .then(response => response.text())
            .then(html => {
                previewContent.innerHTML = html;
                modalPreview.show();
            })
            .catch(err => {
                previewContent.innerHTML = '<p class="text-danger">Erreur de chargement.</p>';
                console.error(err);
            });
    });
});
</script>
@endpush

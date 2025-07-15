@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <div class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.tuteur') }}">DIPRH</a></li>
                                <li class="breadcrumb-item active">
                                    <a href="{{ route('directeur.formulaires.liste') }}">Listes des formulaires</a>
                                </li>
                                <li class="breadcrumb-item active">Formulaire de création</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Créer un nouveau formulaire</h4>
                        <p class="sub-header">Définissez les champs de votre formulaire destinés à tous les tuteurs</p>
                    </div>
                </div>
            </div>

            <!-- formulaire -->
            <div class="row">
                <div class="col-12">


                            <form id="formulaire-creation" action="{{ route('formulaires.store') }}" method="POST">
                                @csrf

                                <!-- titre -->
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre du formulaire <span class="text-danger">*</span></label>
                                    <input type="text" name="titre" id="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                <h5>Champs du formulaire <span class="text-danger">*</span></h5>
                                <div id="champs-container"></div>

                                <button type="button" onclick="ajouterChamp()" class="btn btn-secondary mt-2 mb-3">
                                    <i data-feather="plus" class="me-1"></i> Ajouter un champ
                                </button>

                                <div class="text-end">
                                    <button type="button" id="confirm-submit" class="btn btn-primary">
                                        Créer le formulaire
                                    </button>
                                    <a href="{{ route('directeur.formulaires.liste') }}" class="btn btn-light ms-2">Annuler</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container-fluid -->
    </div> <!-- end content -->
</div> <!-- end container -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let index = 0;

    function ajouterChamp() {
        const container = document.getElementById('champs-container');

        const dernierChamp = container.lastElementChild;
        if (dernierChamp) {
            const labelInput = dernierChamp.querySelector('input[name^="champs"][name$="[label]"]');
            const typeSelect = dernierChamp.querySelector('select[name^="champs"][name$="[type]"]');

            if (!labelInput.value.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Champ manquant',
                    text: "Veuillez remplir le Label du dernier champ avant d'en ajouter un nouveau.",
                    confirmButtonText: 'OK'
                }).then(() => labelInput.focus());
                return;
            }

            if (!typeSelect.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Champ manquant',
                    text: "Veuillez sélectionner le Type du dernier champ avant d'en ajouter un nouveau.",
                    confirmButtonText: 'OK'
                }).then(() => typeSelect.focus());
                return;
            }
        }

        const html = `
        <div class="border rounded p-3 mb-3">
            <div class="mb-3">
                <label class="form-label">Label <span class="text-danger">*</span></label>
                <input type="text" name="champs[${index}][label]" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="champs[${index}][type]" class="form-select" required>
                    <option value="">-- Sélectionner un type --</option>
                    <option value="text">Texte</option>
                    <option value="textarea">Zone de texte</option>
                    <option value="number">Nombre</option>
                    <option value="date">Date</option>
                    <option value="checkbox">Case à cocher</option>
                    <option value="select">Liste déroulante</option>
                </select>
            </div>
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="champs[${index}][requis]" value="1" id="requis_${index}">
                <label class="form-check-label" for="requis_${index}">Champ requis</label>
            </div>
        </div>`;

        container.insertAdjacentHTML('beforeend', html);
        index++;

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    // Confirmation de soumission
    document.getElementById('confirm-submit').addEventListener('click', function () {
        Swal.fire({
            title: 'Confirmer la création',
            text: 'Souhaitez-vous vraiment créer ce formulaire ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Oui, créer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formulaire-creation').submit();
            }
        });
    });

    // Affichage des alertes post redirection
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            confirmButtonColor: '#198754'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Veuillez corriger les erreurs dans le formulaire.',
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
@endpush
